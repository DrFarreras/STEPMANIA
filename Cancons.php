<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">

</head>

<body>

    <div class="Botohome_classificacions">
        <a href='index.html' class="buttonhome"></a><br>
    </div>

    <div class="Llistacancons_cancons">
        <ul class="song_list_ul">

            Llista de cançons<br>
        </ul>
    </div>
    <div class="titols_lista_pantalla_jugar">
        <div class="bloc_esquerra">
            CARATULA
        </div>
        <div class="bloc_central">
            TITOL
        </div>
        <div class="bloc_dreta">
            ARTISTA
        </div>

    </div>

</body>

</html>

<?php

$json = file_get_contents("Data.json");
$contents = json_decode($json, true);


?>

<html>

<body>
    <div class="lista_pantalla_jugar">
        <?php foreach ($contents as $Canco) { ?>

            <div class="bloc_esquerra_caratula">
                <img class="caratula_canco_jugar" src="Uploads/imatge/<?php echo $Canco["Caratula:"]; ?>">
            </div>
            <div class="bloc_central_titol">
                <?php echo $Canco["Titol:"]; ?><br>
            </div>
            <div class="bloc_dreta_artista">
                <?php echo $Canco["Artista:"]; ?>
            </div>
            <div class="buttonedit_delete_play">
                <div class="boto_editar_pantalla_jugar">
                    <a href='Modificarcancons.php' class="buttonedit"></a>
                </div>
                <div class="boto_borrar_pantalla_jugar">
                    <a href="" class="buttondelete"></a>
                </div>
                <div class="boto_jugar_pantalla_jugar">
                    <a href="" class="buttonplay"></a>
                </div>
            </div>
        <?php } ?>
    </div>

</body>


</html>

