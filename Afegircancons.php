<?php
// Archivo JSON
$jsonFile = 'Data.json';

// Llegir el contingut de l'arxiu JSON
$jsonData = file_get_contents($jsonFile);

// Decodificar el JSON en un array associatiu de PHP
$Cancons = json_decode($jsonData, true);

// Obtenir l'ID de la cançó des de la URL
$id = isset($_GET['id']) ? $_GET['id'] : '';
$titol = '';
$artista = "";

// Recorrem totes les cançons per trobar la que coincideix amb l'ID
foreach ($Cancons as $canco) {
    if ($canco["ID"] == $id) {
        $titol = $canco["Titol:"];
        $artista = $canco["Artista:"];
    }
}

// Aquí es fa una substitució dels espais per &nbsp; per conservar-los a la URL
$titol1 = str_replace(' ', '&nbsp;', $titol);
$artist1 = str_replace(' ', '&nbsp;', $artista);
?>


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">

</head>
<body>
    <form action="Llistacancons.php" method="Post" enctype="multipart/form-data">
    <div class="Botonshomereturn">
        <a href='Cancons.php' class="buttonlist"></a>
        <a href='index.html' class="buttonhome"></a><br>
    </div>
    
    <div class="song">
            AFEGIR CANÇONS<br>

        <ul class="song_ul">
            
            <li class="song_ul_li">Títol<input class="song_ul_li_titol" type="text" name="titol" input maxlength="25" required value=<?=$titol1?>></textarea><br></li>
            <li class="song_ul_li">Artista<input class="song_ul_li_artista" type="text" name="artista" input maxlength="15" value=<?=($artist1)?>></textarea><br></li>
            <li class="song_ul_li">Música (.mp3)<input type="file" name="fmusic" id="fmusic" accept="audio/*" required value><br></li>
            <li class="song_ul_li">Caràtula<input type="file" name="fcarat" accept="image/*" required><br></li>
            <li class="song_ul_li">Fitxer de joc<input type="file" name="fjoc" accept="text" required><br></li>
            <li class="song_ul_li">Descripció<input class="song_ul_li_descripcio" type="text" name="descripcio" rows="4" input maxlength="1000" cols="50"></textarea><br></li>

            <input type="submit" class="button_add_song" value="ENVIAR">
            
        </ul>

    </div>
    </form>
</body>
</html>
