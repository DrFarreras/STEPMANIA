<?php
/* CARREGAR EL JSON */
$json = file_get_contents("data.json");
$Cancons = json_decode($json, true);
            
/*GUARDAR EL JSON ACTUALITZAT*/
$json = json_encode(array_values($Cancons)); 
file_put_contents("data.json", $json);

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
    <div class="Botonshomereturn"> <!-- Fem un class dels botons enllaçats amb les seves pàgines corresponents-->

    <a href='Cancons.php' class="buttonreturn"></a><br>
    <a href='index.html' class="buttonhome"></a><br>

    </div>
    <div class="bloc_jugar">
        <div class="blocesquerra_jugar">
            <div class="bloccanco_jugar">CANÇO</div>
            <?php foreach ($Cancons as $canco) {
            $titol = $canco["Titol:"];
            $artista = $canco["Artista:"];
            $id = $canco["ID"];
        ?>
            <div class="caratula_blocesquerra_jugar">
                <img class="caratula_bloc_jugar" src="Uploads/imatge/<?php echo $canco["Caratula:"]; ?>" alt="Carátula">
            </div>
            <div class="titol_bloccentral_jugar">
                <?php echo htmlspecialchars($titol); ?><br>
            </div>
            <div class="artista_blocdreta_jugar">
                <?php echo htmlspecialchars($artista); ?>
            </div>
        <?php } ?>
        </div>
        <div class="bloccentre_jugar">
            <div class="blocjugar_jugar"></div>
        </div>
        <div class="blocdreta_jugar">
            <div class="blocpuntuacio_jugar">PUNTUACIO</div>
        </div> 
    </div>
</body>
</html>