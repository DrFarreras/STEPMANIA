<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.css">    <!-- Enllaçem amb index.css per fer canvis del php-->

</head>
<body>
    <form action="llistacancons.php" method="Post" enctype="multipart/form-data"> <!-- Canviem el metode i afegim el enctype per pujar arxius-->   
    
    <div class="Botonshomereturn"> <!-- Fem un class dels botons enllaçats amb les seves pàgines corresponents-->

        <a href='afegircancons.php' class="buttonreturn"></a><br>
        <a href='index.html' class="buttonhome"></a><br>

    </div>

    </form>
</body>
</html>

<?php       /*Fem un php per poder fer que el formulari envii les dades al json*/
$json = file_get_contents("data.json"); 
$Cancons = json_decode($json, true); /*El arxiu converteix el contingut en un array php*/

$next = count($Cancons) + 1; /*Conta quantes cancons hi ha i suma + 1 per preparar els noms dels nous arxius*/

$Canco = [  /**Crea una array amb la nova informació enviada desde el formulari */
    "ID" => uniqid(number_format(true)), /**fem una funció que genera una id */
    "Titol:" => $_POST["titol"] ,
    "Artista:" => $_POST["artista"],
    "Caratula:" => "img{$next}.jpg",
    "Joc:" => "joc{$next}.jpg",
    "Canco:" => "canco{$next}.mp3"
];
    $Cancons[] = $Canco; /**S'afegeix la nova cancó de l'array cancó al array de cancons, actualitzant l'array final */

    $json = json_encode($Cancons);
    file_put_contents("data.json", $json); /**Guardem l'array actualitzat en data.json */
    
    if (move_uploaded_file($_FILES["fmusic"]["tmp_name"], "Uploads/canco/". $Canco["Canco:"]) && /**Moguem els arxius separats en les carpetes que pertanyin */
    move_uploaded_file($_FILES["fcarat"]["tmp_name"], "Uploads/imatge/". $Canco["Caratula:"]) &&
    move_uploaded_file($_FILES["fjoc"]["tmp_name"], "Uploads/joc/". $Canco["Joc:"]));
    header("location:cancons.php"); /**fem un header location per redirigir al usuari a la pagina de jugar quan afegeixi una canco */


?>




