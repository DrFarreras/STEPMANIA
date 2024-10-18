<?php
session_start(); // iniciar sessio

/* carregar el json */
$json = file_get_contents("data.json");
$Cancons = json_decode($json, true);
$fichero = $Cancons[0]["Joc:"];

/* carregar el contingut del fitxer del joc */
$content = file_get_contents("Uploads/joc/" . $fichero);

/* preparar contingut per les tecles i temps */
$contenido_rythm = str_replace(' ', '', $content); // eliminar espais
$linies = preg_split("/\r\n|\n|\r/", $contenido_rythm); // dividir en linies

$teclas_tiempos = [];
foreach ($linies as $index => $linia) {
    if ($index == 0 || empty(trim($linia))) continue; // saltar primera linia si es buida

    $partes = explode('#', $linia); // dividir per #
    if (count($partes) === 3) {
        list($tecla, $inicio, $final) = $partes;

        // convertir tecles a direccions (coincidiran amb les que s'utilitzaran en javascript)
        switch ($tecla) {
            case 40: $tecla_decimal = "down"; break; // abaix
            case 38: $tecla_decimal = "up"; break;   // amunt
            case 39: $tecla_decimal = "right"; break; // dreta
            case 37: $tecla_decimal = "left"; break; // esquerra
            default: $tecla_decimal = $tecla; break; // per si es una tecla que no coneixem
        }

        // afegir al array
        $teclas_tiempos[] = [
            'tecla' => $tecla_decimal,
            'inicio' => (float)$inicio,
            'final' => (float)$final
        ];
    }
}

/* verificar si s'ha passat un id de canco */
$cancoSeleccionada = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $songId = $_GET['id'];

    // buscar la canco en l'array
    foreach ($Cancons as $canco) {
        if ($canco['ID'] === $songId) {
            $cancoSeleccionada = $canco;
            break;
        }
    }
}

/* si no es troba la canco, es pot gestionar l'error aqui */
if ($cancoSeleccionada === null) {
    die('canco no trobada.');
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($cancoSeleccionada['Titol:']); ?></title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="Botonshomereturn">
        <a href='cancons.php' class="buttonreturn"></a><br>
        <a href='index.html' class="buttonhome"></a><br>
    </div>

    <div class="bloc_jugar">
        <div class="blocesquerra_jugar">
            <div class="bloccanco_jugar">CANCO</div>
            <div class="caratula_blocesquerra_jugar">
                <img class="caratula_bloc_jugar" src="Uploads/imatge/<?php echo htmlspecialchars($cancoSeleccionada['Caratula:']); ?>" alt="caratula">
            </div>
            <div class="titol_bloccentral_jugar">
                <?php echo htmlspecialchars($cancoSeleccionada['Titol:']); ?><br>
            </div>
            <div class="artista_blocdreta_jugar">
                <?php echo htmlspecialchars($cancoSeleccionada['Artista:']); ?>
            </div>
            <audio controls class="reproductor_blocdreta_jugar">
                <source src="Uploads/canco/<?php echo htmlspecialchars($cancoSeleccionada['Canco:']); ?>" type="audio/mpeg">
            </audio>
        </div>

        <div class="bloccentre_jugar">
            <div class="blocjugar_jugar">
                <div id="game-area">
                    <div id="up" class="square"><img src="fletxes/fletxa_adalt.png" alt="amunt"></div>
                    <div id="right" class="square"><img src="fletxes/fletxa_dreta.png" alt="dreta"></div>
                    <div id="down" class="square"><img src="fletxes/fletxa_abaix.png" alt="avall"></div>
                    <div id="left" class="square"><img src="fletxes/fletxa_esquerra.png" alt="esquerra"></div>
                </div>

                <!-- barra de progress -->
                <div class="progress-container">
                    <div class="progress-bar" id="progress-bar"></div>
                </div>
                <!-- boto per iniciar el joc -->
                <button class="start-button" onclick="startGame()">START</button>
                <form id="scoreForm" action="classificacions.php" method="POST" style="display: none;">
                    <input type="hidden" name="playerName" id="playerNameInput">
                    <input type="hidden" name="finalScore" id="finalScoreInput">
                </form>
            </div>
        </div>

        <div class="blocdreta_jugar">
            <div class="blocpuntuacio_jugar">PUNTUACIO
            <div id="score"><span id="points">0</span></div>

            </div>
        </div>
    </div>

    <script>
    // injectar teclas_tiempos des de PHP
    const teclas_tiempos = <?php echo json_encode($teclas_tiempos); ?>;
    
    const scoreDisplay = document.getElementById('points');
    const squares = {
        up: document.getElementById('up'),
        down: document.getElementById('down'),
        left: document.getElementById('left'),
        right: document.getElementById('right')
    };
    let score = 0;
    let isPaused = true;
    let activeSquare = null;
    let intervalId = null;
    let timeoutId = null;

    // obtenir l'element d'audio, la barra de progress i el boto d'inici
    const audio = document.querySelector('audio');
    const progressBar = document.getElementById('progress-bar');
    const startButton = document.querySelector('.start-button');
    const scoreForm = document.getElementById('scoreForm');
    const playerNameInput = document.getElementById('playerNameInput');
    const finalScoreInput = document.getElementById('finalScoreInput');

    const keyMap = {
        'ArrowUp': 'up',
        'ArrowDown': 'down',
        'ArrowLeft': 'left',
        'ArrowRight': 'right'
    };

    let currentIndex = 0;

    function activateSquare() {
        if (isPaused || activeSquare) return;

        const currentEvent = teclas_tiempos[currentIndex];
        if (!currentEvent) return; // si no hi ha mes esdeveniments, sortim

        const direction = currentEvent.tecla;
        activeSquare = squares[direction];
        const img = activeSquare.querySelector('img');
        img.style.display = 'block'; // mostrar la fletxa corresponent

        timeoutId = setTimeout(() => {
            if (activeSquare) {
                updateScore(-50); // penalitza si no es pressiona a temps
                deactivateSquare();
            }
        }, 1000); // temps per pressionar la fletxa

        currentIndex++; // avancem a l'esdeveniment seguent
    }

    function deactivateSquare() {
        if (activeSquare) {
            const img = activeSquare.querySelector('img');
            img.style.display = 'none'; // ocultar la fletxa
            activeSquare = null;
            clearTimeout(timeoutId);
        }
    }

    function updateScore(amount) {
        score += amount;
        scoreDisplay.innerText = score;
    }

    // iniciar el joc quan es pressiona el boto
    function startGame() {
        isPaused = false;
        audio.play();
        intervalId = setInterval(activateSquare, 1500); // interval de temps entre fletxes
        audio.addEventListener('ended', endGame);
        audio.addEventListener('timeupdate', updateProgressBar);
        startButton.classList.add('hidden'); // ocultar el boto de start
    }

    // finalitza el joc quan la canco s'acaba
    function endGame() {
        isPaused = true;
        clearInterval(intervalId); // atura la generacio de fletxes
        deactivateSquare();

        // demanar el nom del jugador
        const playerName = prompt("introdueix el teu nom:");
        if (playerName) {
            playerNameInput.value = playerName;
            finalScoreInput.value = score;
            scoreForm.submit();
        }
    }

    // actualitzar la barra de progress
    function updateProgressBar() {
        const progress = (audio.currentTime / audio.duration) * 100;
        progressBar.style.width = progress + '%';
    }

    // gestionar l'entrada de tecles
    document.addEventListener('keydown', (event) => {
        const direction = keyMap[event.key];
        if (!direction || !activeSquare) return; // ignorar si no es direccio valida o no hi ha fletxa activa

        if (activeSquare.id === direction) {
            updateScore(100); // incrementar puntuacio si es correcte
        } else {
            updateScore(-50); // penalitzar si no coincideix
        }

        deactivateSquare();
    });
</script>

</body>
</html>
