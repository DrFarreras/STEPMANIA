
<?php
// Archivo JSON
$jsonFile = 'Data.json';

// Leer el contenido del archivo JSON
$jsonData = file_get_contents($jsonFile);

// Decodificar el JSON en un array asociativo de PHP
$canco = json_decode($jsonData, true);

// Obtener el ID de la canción desde la URL
$titol = isset($_GET['titol']) ? $_GET['titol'] : '';
$artista = isset($_GET['artista']) ? $_GET['artista']: '';

// Obtener la canción seleccionada según el ID
$selectedSong = isset($canco[$titol]) ? $canco[$titol] : null;
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
        <a href='index.html' class="buttonhome"></a><br>
        
    </div>
    
    <div class="song">
            AFEGIR CANÇONS<br>

        <ul class="song_ul">
            
            <li class="song_ul_li">Títol<input class="song_ul_li_titol" type="text" name="titol" input maxlength="15" required value=<?=$titol?>></textarea><br></li>
            <li class="song_ul_li">Artista<input class="song_ul_li_artista" type="text" name="artista" required value=<?=urldecode($artista)?>></textarea><br></li>
            <li class="song_ul_li">Música (.mp3)<input type="file" name="fmusic" id="fmusic" accept="audio/*" required value><br></li>
            <li class="song_ul_li">Caràtula<input type="file" name="fcarat" accept="image/*" required><br></li>
            <li class="song_ul_li">Fitxer de joc<input type="file" name="fjoc" accept="text" required><br></li>
            <li class="song_ul_li">Descripció<input class="song_ul_li_descripcio" type="text" name="descripcio" rows="4" cols="50"></textarea><br></li>

            <input type="submit" class="button_add_song" value="ENVIAR">
            
        </ul>

    </div>
    </form>
</body>
</html>
