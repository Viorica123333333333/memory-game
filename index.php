<?php
// 1. DATABASE CONNECTION AND DATA PREPARATION
//---------------------------------------------------------

include "db.php";

// Retrieve only needed columns
$sql = "SELECT id, image FROM pairs";
$result = $conn->query($sql);

$cards = [];

while ($row = $result->fetch_assoc()) {

    // Allow only safe PNG file names
    if (preg_match('/^[a-zA-Z0-9_-]+\.png$/', $row["image"])) {
        $cards[] = $row;
        $cards[] = $row;
    }
}

shuffle($cards);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1 class="game-title">Memory Game</h1>

    <p class="game-instructions">
        Train your memory and enjoy the game!
        The rules are simple: find all the matches in only 3 tries!
    </p>

    <div class="game-container">

        <a href="login.php" id="admin-button">Admin Login</a>

        <p id="status-message">Wrong attempts: 0 / 3</p>
        <p id="score-message">Score: 0</p>
        <p id="score-popup"></p>
        <p id="preview-message">Memorise the cards: 5</p>

        <button id="restart-button" onclick="location.reload()">Restart Game</button>

        <div class="board-wrapper">
            <div class="game-board">
                <?php foreach ($cards as $card) { ?>
                    <div
                        class="card"
                        data-pair="<?php echo htmlspecialchars($card['id'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-image="images/<?php echo htmlspecialchars($card['image'], ENT_QUOTES, 'UTF-8'); ?>"
                    >
                        <img
                            src="images/<?php echo htmlspecialchars($card['image'], ENT_QUOTES, 'UTF-8'); ?>"
                            alt="Game card"
                        >
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>

    <audio id="flip-sound" src="audio/flip.mp3"></audio>
    <audio id="match-sound" src="audio/match.mp3"></audio>
    <audio id="wrong-sound" src="audio/wrong.mp3"></audio>
    <audio id="win-sound" src="audio/win.mp3"></audio>
    <audio id="gameover-sound" src="audio/gameover.mp3"></audio>

    <script src="js/script.js"></script>

<footer class="footer">
    <p>© 2026 Educational project. Images and audio from royalty-free sources (Unsplash, Pixabay).</p>
</footer>

</body>
</html>