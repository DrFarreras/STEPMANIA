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
            
            Llista de cançons<br><br>
            
        </ul>
    
        
        
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
            <?php foreach($contents as $Canco){ ?>
                    <img class="caratula_canco_jugar"src="Uploads/imatge/<?php echo $Canco["Caratula:"];?>">
                
                <div class="cancons_pantalla_jugar">
                    <?php echo $Canco["Titol:"];?>
                </div>
                
                
            <?php } ?>
        </div>
        <div class="boto_foot_cancons"> 
            <button class="btn-jugar-cancons">JUGAR</button>
        </div>
    </body>
</html>

