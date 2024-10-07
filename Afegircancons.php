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
        <a href='Modificarcancons.php' class="buttonedit"></a><br>
        <a href='index.html' class="buttonhome"></a><br>
        
    </div>
    
    <div class="song">
            AFEGIR CANÇONS<br>

        <ul class="song_ul">
            
            <li class="song_ul_li">Títol<textarea type="text" name="titol" rows="2" cols="50" required></textarea><br></li>
            <li class="song_ul_li">Artista<textarea type="text" name="artista" rows="2" cols="50" required></textarea><br></li>
            <li class="song_ul_li">Música (.mp3)<input type="file" name="fmusic" id="fmusic" accept="audio/*" required><br></li>
            <li class="song_ul_li">Caràtula<input type="file" name="fcarat" accept="image/*" required><br></li>
            <li class="song_ul_li">Fitxer de joc<input type="file" name="fjoc" accept="text" required><br></li>
            <li class="song_ul_li">Descripció<textarea name="descripcio" rows="4" cols="50"></textarea><br></li>

            <input type="submit" class="button_add_song" value="ENVIAR">
            
        </ul>
    

    </div>
    </form>
</body>
</html>
