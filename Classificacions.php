<?php
session_start(); // iniciar la sessio

// archivo json donde guardaremos les puntuacions
$filename = 'scores.json';

// funcio per llegir el fitxer json
function readScores($filename) {
    if (file_exists($filename)) {
        $jsonData = file_get_contents($filename);
        return json_decode($jsonData, true);
    }
    return [];
}

// funcio per guardar les puntuacions al fitxer json
function saveScores($filename, $scores) {
    $jsonData = json_encode($scores, JSON_PRETTY_PRINT);
    file_put_contents($filename, $jsonData);
}

// carregar les puntuacions existents
$scores = readScores($filename);

// guardar la puntuacio i el nom a la sessio i al fitxer json
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $playerName = $_POST['playerName'];
    $finalScore = $_POST['finalScore'];

    // afegir la nova puntuacio
    $newEntry = [
        'player' => $playerName,
        'score' => $finalScore
    ];
    $scores[] = $newEntry;

    // guardar la puntuacio a la sessio
    $_SESSION['scores'] = $scores;

    // guardar la puntuacio al fitxer json
    saveScores($filename, $scores);
}
    // ordenar les puntuacions de mes a menys
    usort($scores, function($a, $b) {
        return $b['score'] - $a['score'];
    });
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
    
    <div class="classificacions_taula_punts">
        <div class="jugador_classificacio"> <div class="juga_class">JUGADOR</div>
            <?php
            // mostrar les puntuacions des del fitxer json
            if (!empty($scores)) {
                echo "<ul>";
                foreach ($scores as $entry) {
                    echo "<li>";
                    // Mostrar el jugador
                    echo "<div class='player_classificacions'>" . htmlspecialchars($entry['player']) . "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>

        <div class="puntuacio_classificacio"> <div class="punt_class">PUNTUACIO</div>
            <?php
            // mostrar les puntuacions des del fitxer json
            if (!empty($scores)) {
                echo "<ul>";
                foreach ($scores as $entry) {
                    echo "<li>";
                    // Mostrar la puntuació en una nova línia
                    echo "<div class='score_classificacions'>" . htmlspecialchars($entry['score']) . "</div>";
                    echo "</li>";
                }
                echo "</ul>";
            }
            ?>
        </div>

        <div class="linea_classificacio"></div>

        
    </div>

</body>
</html>
