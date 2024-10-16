<?php
/**json */
$jsonFile = 'data.json';

/**llegeix el contingut del json */
$jsonData = file_get_contents($jsonFile);

/**decodifica el json en un array de PHP*/
$Cancons = json_decode($jsonData, true);

/**obte id del array desde url */
$id = isset($_GET['id']) ? $_GET['id'] : '';
$titol = '';
$artista = '';

/** Si existe una ID, recorre las canciones para encontrar la que coincida y obtener los datos */
if ($id) {
    foreach ($Cancons as $key => $canco) {
        if ($canco['ID'] == $id) {
            $titol = $canco['Titol:'];
            $artista = $canco['Artista:'];
        }
    }
}

/**Aquí es fa una substitució dels espais per &nbsp; per conservar-los a la URL */
$titol1 = str_replace(' ', '&nbsp;', $titol);
$artist1 = str_replace(' ', '&nbsp;', $artista);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="index.css">
    <script>
        function validateForm(event) {
            const fileJoc = document.getElementById('fjoc').value;
            const description = document.getElementById('descripcio').value;

            /** Si ambos campos están llenos, muestra una alerta y evita el envío */
            if (fileJoc && description) {
                event.preventDefault();
                alert("NOMES POTS ESCOLLIR UN CAMP: 'FITXER JOC' O 'DESCRIPCIO'.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <form action="guardarcanco.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event);">
        <div class="Botonshomereturn">
            <a href='cancons.php' class="buttonlist"></a>
            <a href='index.html' class="buttonhome"></a><br>
        </div>
        
        <div class="song">
            CANÇONS<br>

            <ul class="song_ul">
                <li class="song_ul_li">Títol<input class="song_ul_li_titol" type="text" name="titol" required input maxlength="25" required value="<?=$titol1?>"><br></li>
                <li class="song_ul_li">Artista<input class="song_ul_li_artista" type="text" name="artista" required input maxlength="15" value="<?=($artist1)?>"><br></li>
                <li class="song_ul_li">Música (.mp3): <input type="file" name="fmusic" id="fmusic" required accept="audio/*"></li><br>
                <li class="song_ul_li">Caràtula: <input type="file" name="fcarat" required accept="image/*"></li><br>
                <li class="song_ul_li">Fitxer de joc: <input type="file" name="fjoc" id="fjoc" accept="text"></li><br>
                <li class="song_ul_li">Descripció: <input class="song_ul_li_descripcio" type="text" name="descripcio" id="descripcio" maxlength="1000" rows="4" cols="50"></li><br>

                <input type="hidden" name="id" value="<?=$id?>">
                <input type="submit" class="button_add_song" value="ENVIAR"><br>
            </ul>
        </div>
    </form>
</body>
</html>
