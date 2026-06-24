<?php
// 1. SESSION SECURITY
//---------------------------------------------------------

session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}

// 2. DATABASE CONNECTION
//---------------------------------------------------------

include "db.php";

$error = "";

// 3. HANDLE FORM SUBMISSION
//---------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $image = trim($_POST["image"] ?? "");

    if (!preg_match('/^[a-zA-Z0-9_-]+\.png$/', $image)) {

        $error = "Only PNG image file names are allowed, for example: image.png.";

    } else {

        $stmt = $conn->prepare("INSERT INTO pairs (image) VALUES (?)");
        $stmt->bind_param("s", $image);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            $error = "Unable to add image.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Pair</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="game-container">

        <a href="admin.php" class="top-left-link">Back to Admin Panel</a>

        <h1 class="game-title">Add New Pair</h1>
        <p class="game-instructions">
            Enter the image file name to add a new matching pair.
        </p>

        <?php if (!empty($error)) { ?>
            <p style="color: #c62828; font-weight: bold; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php } ?>

        <form method="POST" class="admin-form">
            <input
                type="text"
                name="image"
                placeholder="Image file name (e.g. image.png)"
                required
            >

            <button type="submit">Add Pair</button>
        </form>

    </div>

<footer class="footer">
    <p>© 2026 Educational project. Images and audio from royalty-free sources (Unsplash, Pixabay).</p>
</footer>

</body>
</html>