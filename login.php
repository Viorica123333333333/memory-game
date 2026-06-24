<?php
session_start();

// Define fixed admin credentials
$valid_username = "admin";
$valid_password = "ChangeThisPassword";

// Variable to store error message
$error = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // Validate login credentials
    if ($username === $valid_username && $password === $valid_password) {

        // Prevent session fixation attacks
        session_regenerate_id(true);

        $_SESSION["admin_logged_in"] = true;

        // Redirect to admin panel after successful login
        header("Location: admin.php");
        exit();

    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="game-container">

        <a href="index.php" class="top-left-link">Back to Game</a>

        <h1 class="game-title">Admin Login</h1>
        <p class="game-instructions">Enter the administrator credentials.</p>

        <form method="POST" class="admin-form">
            <input
                type="text"
                name="username"
                placeholder="Username"
                required
                autocomplete="username"
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
                autocomplete="current-password"
            >

            <button type="submit">Login</button>
        </form>

        <?php if (!empty($error)) { ?>
            <p style="color: red; margin-top: 15px;">
                <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
            </p>
        <?php } ?>

    </div>

    <footer class="footer">
        <p>© 2026 Educational project. Images and audio from royalty-free sources (Unsplash, Pixabay).</p>
    </footer>

</body>
</html>