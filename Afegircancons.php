<?php
/**json */
$jsonFile = 'Data.json';

/**llegeix el contingut del json */
$jsonData = file_get_contents($jsonFile);

/**decodifica el json en un array de PHP*/
$Cancons = json_decode($jsonData, true);

/**obte id del array desde url */
$id = isset($_GET['id']) ? $_GET['id'] : '';
$titol = '';
$artista = "";

/**recorre totes les cancons per trovar la que tingui la mateixa id */
foreach ($Cancons as $canco) {
    if ($canco["ID"] == $id) {
        $titol = $canco["Titol:"];
        $artista = $canco["Artista:"];
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
    <title>Document</title>
    <link rel="stylesheet" href="index.css">
    <script>
        function validateForm(event) {
            const fileJoc = document.getElementById('fjoc').value;
            const description = document.getElementById('descripcio').value;

            /**si els dos camps (descripcio) i (fitxer de joc), executa el event. el && es un i */
            if (fileJoc && description) {
                event.preventDefault(); /**evita que el formulari senvii */
                alert("NOMES POTS ESCOLLIR UN CAMP: 'FITXER JOC' O 'DESCRIPCIO'.");
                return false;
            }

            return true;
        }
    </script>
</head>
<body>
    <form action="Llistacancons.php" method="POST" enctype="multipart/form-data" onsubmit="return validateForm(event);">
        <div class="Botonshomereturn">
            <a href='Cancons.php' class="buttonlist"></a>
            <a href='index.html' class="buttonhome"></a><br>
        </div>
        
        <div class="song">
            AFEGIR CANÇONS<br>

            <ul class="song_ul">
                <li class="song_ul_li">Títol<input class="song_ul_li_titol" type="text" name="titol" input maxlength="25" required value=<?=$titol1?>></textarea><br></li>
                <li class="song_ul_li">Artista<input class="song_ul_li_artista" type="text" name="artista" input maxlength="15" value=<?=($artist1)?>></textarea><br></li>
                <li class="song_ul_li">Música (.mp3): <input type="file" name="fmusic" id="fmusic" accept="audio/*" required></li><br>
                <li class="song_ul_li">Caràtula: <input type="file" name="fcarat" accept="image/*" required></li><br>
                <li class="song_ul_li">Descripció: <input class="song_ul_li_descripcio" type="text" name="descripcio" id="descripcio" maxlength="1000" rows="4" cols="50"></li><br>
                <li class="song_ul_li">Fitxer de joc: <input type="file" name="fjoc" id="fjoc" accept="text"></li><br>

                <input type="submit" class="button_add_song" value="ENVIAR"><br>
            </ul>
        </div>
    </form>
</body>
</html>
