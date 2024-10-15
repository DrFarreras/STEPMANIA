<?php
/* CARREGAR EL JSON */
$json = file_get_contents("data.json");
$Cancons = json_decode($json, true);

/* Verificar si se ha pasado un ID de canción */
$cancoSeleccionada = null;
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $songId = $_GET['id'];

    // Buscar la canción en el array
    foreach ($Cancons as $canco) {
        if ($canco['ID'] === $songId) {
            $cancoSeleccionada = $canco; // Aquí asignamos la canción seleccionada
            break;
        }
    }
}

/* Si no se encuentra la canción, se puede manejar el error aquí */
if ($cancoSeleccionada === null) {
    die('Cançó no trobada.');
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
        <a href='Cancons.php' class="buttonreturn"></a><br>
        <a href='index.html' class="buttonhome"></a><br>
    </div>

    <div class="bloc_jugar">
        <div class="blocesquerra_jugar">
            <div class="bloccanco_jugar">CANÇO</div>
            <div class="caratula_blocesquerra_jugar">
                <img class="caratula_bloc_jugar" src="Uploads/imatge/<?php echo htmlspecialchars($cancoSeleccionada['Caratula:']); ?>" alt="Carátula">
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
                <div id="score">Puntuació: <span id="points">0</span></div>
                <div id="game-area">
                    <div id="up" class="square"><img src="fletxes/fletxa_adalt.png" alt="Amunt"></div>
                    <div id="right" class="square"><img src="fletxes/fletxa_dreta.png" alt="Dreta"></div>
                    <div id="down" class="square"><img src="fletxes/fletxa_abaix.png" alt="Avall"></div>
                    <div id="left" class="square"><img src="fletxes/fletxa_esquerra.png" alt="Esquerra"></div>
                </div>
                <!-- Botón para iniciar el juego -->
                <button class="start-button" onclick="startGame()">Començar Joc</button>
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
                    let audio = document.getElementById('background-audio');

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

                        setTimeout(() => {
                            if (activeSquare) {
                                updateScore(-50);
                                deactivateSquare();
                            }
                        }, 1000);
                    }

                    function deactivateSquare() {
                        if (activeSquare) {
                            const img = activeSquare.querySelector('img');
                            img.style.display = 'none';
                            activeSquare = null;
                        }
                    }

                    function updateScore(amount) {
                        score += amount;
                        scoreDisplay.innerText = score;
                    }

                    function startGame() {
                        isPaused = false;
                        audio.play();

                        setInterval(activateSquare, 1500);
                    }

                    document.addEventListener('keydown', (event) => {
                        if (isPaused || !activeSquare) return;

                        const keyMap = {
                            'ArrowUp': 'up',
                            'ArrowDown': 'down',
                            'ArrowLeft': 'left',
                            'ArrowRight': 'right'
                        };

                        const pressedKey = keyMap[event.key];
                        if (pressedKey) {
                            if (activeSquare.id === pressedKey) {
                                updateScore(100);
                            } else {
                                updateScore(-50);
                            }
                            deactivateSquare();
                        }
                    });
                </script>
</body>

</html>
</div>
</div>
<div class="blocdreta_jugar">
    <div class="blocpuntuacio_jugar">PUNTUACIO</div>
</div>
</div>
</body>

</html>