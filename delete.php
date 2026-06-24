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

// 3. DELETE OPERATION
//------------------------------------------------------------

if (isset($_GET["id"])) {

    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id === false || $id === null) {
        die("Invalid ID.");
    }

    $stmt = $conn->prepare("DELETE FROM pairs WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        header("Location: admin.php");
        exit();

    } else {

        echo "Unable to delete record.";

    }

    $stmt->close();

} else {

    echo "No ID provided.";

}
?>