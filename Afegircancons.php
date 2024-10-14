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

            // Si ambos campos están llenos, evitar el envío del formulario y mostrar un mensaje de error
            if (fileJoc && description) {
                event.preventDefault(); // Evitar que el formulario se envíe
                alert("Error: Debes elegir solo uno de los campos: 'Fitxer de joc' o 'Descripció'."); // Mensaje de error
                return false; // Opcional, para mayor claridad
            }

            return true; // O puedes omitir esto, ya que no se usará en este contexto
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
                <li class="song_ul_li">Títol: <input class="song_ul_li_titol" type="text" name="titol" maxlength="25" required value="<?= htmlspecialchars($titol1) ?>"></li><br>
                <li class="song_ul_li">Artista: <input class="song_ul_li_artista" type="text" name="artista" maxlength="15" value="<?= htmlspecialchars($artist1) ?>"></li><br>
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
