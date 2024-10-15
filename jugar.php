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
                <!-- Barra de progreso -->
            <div class="progress-container">
            <div class="progress-bar" id="progress-bar"></div>
            </div>
                <!-- Botón para iniciar el juego -->
                <button class="start-button" onclick="startGame()">START</button>
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

    // Obtener el elemento de audio, la barra de progreso y el botón de inicio
    const audio = document.querySelector('audio');
    const progressBar = document.getElementById('progress-bar');
    const startButton = document.querySelector('.start-button');

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
                updateScore(-50); // Penaliza si no se presiona a tiempo
                deactivateSquare();
            }
        }, 1000); // Tiempo para pulsar la flecha
    }

    function deactivateSquare() {
        if (activeSquare) {
            const img = activeSquare.querySelector('img');
            img.style.display = 'none';
            activeSquare = null;
            clearTimeout(timeoutId); // Evita que el timeout siga si se desactiva antes
        }
    }

    function updateScore(amount) {
        score += amount;
        scoreDisplay.innerText = score;
    }

    // Iniciar el juego cuando se presiona el botón
    function startGame() {
        isPaused = false;

        // Reproducir la canción
        audio.play();

        // Iniciar el intervalo para las flechas
        intervalId = setInterval(activateSquare, 1500); // Aparece una nueva flecha cada 1.5 segundos

        // Cuando la canción termine, acabar el juego
        audio.addEventListener('ended', endGame);

        // Actualizar la barra de progreso en tiempo real
        audio.addEventListener('timeupdate', updateProgressBar);

        // Desaparecer el botón de inicio
        startButton.classList.add('hidden');
    }

    // Finaliza el juego cuando la canción se acaba
    function endGame() {
        isPaused = true;
        clearInterval(intervalId); // Detiene la generación de flechas
        deactivateSquare(); // Desactiva la flecha activa

        // Mostrar mensaje de fin de juego
        alert("La canción ha terminado. Puntuación final: " + score);
    }

    // Actualizar la barra de progreso
    function updateProgressBar() {
        const currentTime = audio.currentTime; // Tiempo actual de la canción
        const duration = audio.duration; // Duración total de la canción

        if (!isNaN(duration)) { // Evitar errores si la duración no está disponible
            const progressPercent = (currentTime / duration) * 100;
            progressBar.style.width = `${progressPercent}%`;
        }
    }

    // Manejo del evento keydown para detectar flechas del teclado
    document.addEventListener('keydown', (event) => {
        if (isPaused || !activeSquare) return;

        const pressedKey = keyMap[event.key];

        if (pressedKey) {
            if (activeSquare.id === pressedKey) {
                updateScore(100); // Acierto
            } else {
                updateScore(-50); // Error
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