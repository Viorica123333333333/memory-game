<?php
// 1. SESSION SECURITY
//----------------------------------------------------------

session_start();

if (!isset($_SESSION["admin_logged_in"])) {
    header("Location: login.php");
    exit();
}

// 2. DATABASE CONNECTION AND DATA RETRIEVAL
//----------------------------------------------------------

include "db.php";

$sql = "SELECT id, image FROM pairs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="game-container">

        <a href="index.php" class="top-left-link">Back to Game</a>
        <a href="logout.php" class="top-right-link">Logout</a>

        <h1 class="game-title">Admin Panel</h1>
        <p class="game-instructions">Manage memory game pairs.</p>

        <table class="admin-table">
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["id"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($row["image"], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a
                            href="edit.php?id=<?php echo urlencode($row['id']); ?>"
                            class="edit-link"
                        >
                            Edit
                        </a>
                        |
                        <a
                            href="delete.php?id=<?php echo urlencode($row['id']); ?>"
                            class="delete-link"
                            onclick="return confirm('Are you sure you want to delete this pair?');"
                        >
                            Delete
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <a href="add.php" class="small-link-button">Add New Pair</a>

    </div>

<footer class="footer">
    <p>© 2026 Viorica Pogor. Educational project. Images and audio from royalty-free sources (Unsplash, Pixabay).</p>
</footer>

</body>
</html>