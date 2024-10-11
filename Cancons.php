<?php
// Cargar el archivo JSON
$json = file_get_contents("data.json");
$Cancons = json_decode($json, true);

// Verificar si se quiere eliminar una canción
if (isset($_GET['delete'])) {
    $idToDelete = $_GET['delete'];

    // Buscar la canción por su ID y eliminarla
    foreach ($Cancons as $key => $canco) {
        if ($canco['ID'] === $idToDelete) {
            // Eliminar archivos relacionados
            if (file_exists("Uploads/canco/" . $canco["Canco:"])) {
                unlink("Uploads/canco/" . $canco["Canco:"]);
            }
            if (file_exists("Uploads/imatge/" . $canco["Caratula:"])) {
                unlink("Uploads/imatge/" . $canco["Caratula:"]);
            }
            if (file_exists("Uploads/joc/" . $canco["Joc:"])) {
                unlink("Uploads/joc/" . $canco["Joc:"]);
            }

            // Eliminar la canción del array
            unset($Cancons[$key]);
        }
    }

    // Guardar el JSON actualizado
    $json = json_encode(array_values($Cancons)); // Re-indexar el array
    file_put_contents("data.json", $json);

    // Redirigir de nuevo a la lista
    header("Location: Cancons.php");
    exit();
}
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

    <div class="lista_pantalla_jugar">
        <?php foreach ($Cancons as $canco) {
            $titol = $canco["Titol:"];
            $artista = $canco["Artista:"];
            $id=$canco["ID"];
        ?>
            <div class="bloc_esquerra_caratula">
                <img class="caratula_canco_jugar" src="Uploads/imatge/<?php echo $canco["Caratula:"]; ?>" alt="Carátula">
            </div>
            <div class="bloc_central_titol">
                <?php echo htmlspecialchars($titol); ?><br>
            </div>
            <div class="bloc_dreta_artista">
                <?php echo htmlspecialchars($artista); ?>
            </div>
            <div class="buttonedit_delete_play">
                <div class="boto_editar_pantalla_jugar">
                    <a href='Afegircancons.php?id=<?=$id?>' class="buttonedit"></a>
                </div>
                <div class="boto_borrar_pantalla_jugar">
                    <a href='Cancons.php?delete=<?= urlencode($canco["ID"]) ?>' class="buttondelete" onclick="return confirm('Vols eliminar aquesta cançó?')"></a>
                </div>
                <div class="boto_jugar_pantalla_jugar">
                <a href="" class="buttonplay"></a>
                </div>
            </div>
        <?php } ?>
    </div>
</body>
</html>
