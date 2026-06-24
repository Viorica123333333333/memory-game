<?php
// 1. SESSION SECURITY
//------------------------------------------------------------

session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}

// 2. DATABASE CONNECTION
//------------------------------------------------------------

include "db.php";

$error = "";
$pair = null;

// 3. HANDLE FORM SUBMISSION
//------------------------------------------------------------

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
    $image = trim($_POST["image"] ?? "");

    if ($id === false || $id === null) {
        $error = "Invalid ID.";
    } elseif (!preg_match('/^[a-zA-Z0-9_-]+\.png$/', $image)) {
        $error = "Only PNG image file names are allowed, for example: image1.png.";

        $pair = [
            "id" => $id,
            "image" => $image
        ];
    } else {
        $stmt = $conn->prepare("UPDATE pairs SET image = ? WHERE id = ?");
        $stmt->bind_param("si", $image, $id);

        if ($stmt->execute()) {
            header("Location: admin.php");
            exit();
        } else {
            $error = "Unable to update image.";
        }

        $stmt->close();
    }
}

// 4. LOAD CURRENT PAIR DATA
//------------------------------------------------------------

if ($pair === null && isset($_GET["id"])) {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    if ($id === false || $id === null) {
        die("Invalid ID.");
    }

    $stmt = $conn->prepare("SELECT id, image FROM pairs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $pair = $result->fetch_assoc();

    $stmt->close();
}

if ($pair === null) {
    die("Pair not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pair</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="game-container">

        <a href="admin.php" class="top-right-link">Go to Admin Panel</a>

        <h1 class="game-title">Edit Pair</h1>
        <p class="game-instructions">Update the image file name.</p>

        <?php if (!empty($error)) { ?>
            <p style="color: #c62828; font-weight: bold; margin-bottom: 15px;">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php } ?>

        <form method="POST" class="admin-form">
            <input
                type="hidden"
                name="id"
                value="<?php echo htmlspecialchars($pair['id'], ENT_QUOTES, 'UTF-8'); ?>"
            >

            <input
                type="text"
                name="image"
                value="<?php echo htmlspecialchars($pair['image'], ENT_QUOTES, 'UTF-8'); ?>"
                required
            >

            <button type="submit">Update Pair</button>
        </form>

    </div>

<footer class="footer">
    <p>© 2026 Educational project. Images and audio from royalty-free sources (Unsplash, Pixabay).</p>
</footer>

</body>
</html>