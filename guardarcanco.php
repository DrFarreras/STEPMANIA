<?php
/* Carregar el JSON */
$jsonFile = 'data.json';
$jsonData = file_get_contents($jsonFile);
$Cancons = json_decode($jsonData, true);

/** Obtenir dades del formulari */
$id = isset($_POST['id']) ? $_POST['id'] : '';
$titol = isset($_POST['titol']) ? str_replace('&nbsp;', ' ', $_POST['titol']) : '';
$artista = isset($_POST['artista']) ? str_replace('&nbsp;', ' ', $_POST['artista']) : '';

/* Variables per arxius pujats */
$fmusic = '';
$fcarat = '';
$fjoc = '';

/* Verificar i guardar els arxius pujats */
if (isset($_FILES['fmusic']['name']) && $_FILES['fmusic']['name']) {
    $fmusic = $_FILES['fmusic']['name'];
    move_uploaded_file($_FILES['fmusic']['tmp_name'], "Uploads/canco/" . $fmusic);
}

if (isset($_FILES['fcarat']['name']) && $_FILES['fcarat']['name']) {
    $fcarat = $_FILES['fcarat']['name'];
    move_uploaded_file($_FILES['fcarat']['tmp_name'], "Uploads/imatge/" . $fcarat);
}

if (isset($_FILES['fjoc']['name']) && $_FILES['fjoc']['name']) {
    $fjoc = $_FILES['fjoc']['name'];
    move_uploaded_file($_FILES['fjoc']['tmp_name'], "Uploads/joc/" . $fjoc);
}

/** Si se envia una ID, modificar la canco existent */
if ($id) {
    foreach ($Cancons as $key => $canco) {
        if ($canco['ID'] === $id) {
            $Cancons[$key]['Titol:'] = $titol;
            $Cancons[$key]['Artista:'] = $artista;
            if ($fmusic) $Cancons[$key]['Canco:'] = $fmusic;
            if ($fcarat) $Cancons[$key]['Caratula:'] = $fcarat;
            if ($fjoc) $Cancons[$key]['Joc:'] = $fjoc;
        }
    }
} else {
    /** Si no hi ha ID, es crea una nova canco */
    $newCanco = [
        'ID' => uniqid(),
        'Titol:' => $titol,
        'Artista:' => $artista,
        'Canco:' => $fmusic,
        'Caratula:' => $fcarat,
        'Joc:' => $fjoc
    ];
    $Cancons[] = $newCanco;
}

/** Guardar el JSON actualitzat */
file_put_contents($jsonFile, json_encode($Cancons));

/** Redirigir a cancons.php */
header("Location: cancons.php");
exit();
?>
