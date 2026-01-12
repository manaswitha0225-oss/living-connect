<?php
session_start();
// Ensure admin is logged in and this is a POST request
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

require_once '../php/db_connect.php';

if (isset($_POST['user_id'])) {
    $user_id_to_delete = intval($_POST['user_id']);

    // Prevent admin from deleting their own 'users' table entry if it exists
    // A better approach is separate admin/user tables, which we have.
    // This is a good safeguard anyway.
    if (isset($_SESSION['user_id']) && $user_id_to_delete == $_SESSION['user_id']) {
        // You might want to add an error message here
        header("Location: manage_users.php?error=selfdelete");
        exit();
    }

    // Prepare and execute the deletion
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id_to_delete);
    
    if ($stmt->execute()) {
        // Redirect back with a success message
        header("Location: manage_users.php?status=deleted");
    } else {
        // Redirect back with an error message
        header("Location: manage_users.php?error=deletefailed");
    }
    exit();
}

// Redirect if no user_id is provided
header("Location: manage_users.php");
exit();
?>
