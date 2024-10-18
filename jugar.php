<?php
session_start(); // iniciar sessio

/* carregar el json */
$json = file_get_contents("data.json");
$Cancons = json_decode($json, true);

/* verificar si s'ha passat un id de canco */
$cancoSeleccionada = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $songId = $_GET['id'];

    // buscar la canco en l'array
    foreach ($Cancons as $canco) {
        if ($canco['ID'] === $songId) {
            $cancoSeleccionada = $canco; // aqui assignem la canco seleccionada
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
                <div id="score">PUNTUACIO: <span id="points">0</span></div>
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
            <div class="blocpuntuacio_jugar">PUNTUACIO</div>
        </div>
    </div>

    <script>
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

        function randomSquare() {
            const directions = ['up', 'down', 'left', 'right'];
            return directions[Math.floor(Math.random() * 4)];
        }

        function activateSquare() {
            if (isPaused || activeSquare) return;

            const direction = randomSquare();
            activeSquare = squares[direction];
            const img = activeSquare.querySelector('img');
            img.style.display = 'block';

            timeoutId = setTimeout(() => {
                if (activeSquare) {
                    updateScore(-50); // penalitza si no es pressiona a temps
                    deactivateSquare();
                }
            }, 1000); // temps per pressionar la fletxa
        }

        function deactivateSquare() {
            if (activeSquare) {
                const img = activeSquare.querySelector('img');
                img.style.display = 'none';
                activeSquare = null;
                clearTimeout(timeoutId); // evita que el timeout segueixi si es desactiva abans
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
            intervalId = setInterval(activateSquare, 1500); // apareix una nova fletxa cada 1.5 segons
            audio.addEventListener('ended', endGame);
            audio.addEventListener('timeupdate', updateProgressBar);
            startButton.classList.add('hidden');
        }

        // finalitza el joc quan la canco s'acaba
        function endGame() {
            isPaused = true;
            clearInterval(intervalId); // atura la generacio de fletxes
            deactivateSquare(); // desactiva la fletxa activa

            // demanar el nom del jugador
            const playerName = prompt("introdueix el teu nom:");
            if (playerName) {
                // enviar el nom i la puntuacio a php
                playerNameInput.value = playerName;
                finalScoreInput.value = score;
                scoreForm.submit();
            }
        }

        // actualitzar la barra de progress
        function updateProgressBar() {
            const currentTime = audio.currentTime;
            const duration = audio.duration;

            if (!isNaN(duration)) {
                const progressPercent = (currentTime / duration) * 100;
                progressBar.style.width = `${progressPercent}%`;
            }
        }

        document.addEventListener('keydown', (event) => {
            if (isPaused || !activeSquare) return;

            const pressedKey = keyMap[event.key];

            if (pressedKey) {
                if (activeSquare.id === pressedKey) {
                    updateScore(100); // encert
                } else {
                    updateScore(-50); // error
                }
                deactivateSquare();
            }
        });
    </script>
</body>
</html>
