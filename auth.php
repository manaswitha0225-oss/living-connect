<?php
session_start();
// This path assumes db_connect.php is one level up from the 'admin' folder.
require_once '../php/db_connect.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['login-email']);
    $password = trim($_POST['login-password']);

    if (empty($email) || empty($password)) {
        // Redirect back to the login page with an error
        header("Location: index.php?error=1");
        exit();
    }

    // Prepare a statement to find the user in the 'admins' table.
    $sql = "SELECT id, full_name, password_hash FROM admins WHERE email = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        // Handle SQL prepare error
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($admin = $result->fetch_assoc()) {
        // Admin found, now verify the hashed password.
        if (password_verify($password, $admin['password_hash'])) {
            // Credentials are correct.
            
            // Start the secure admin session.
            session_regenerate_id(true); // Prevents session fixation
            $_SESSION['admin_loggedin'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['full_name'];

            // CORRECTED: Redirect to the admin dashboard in the SAME folder.
            header("Location: dashboard.php");
            exit();
        }
    }
    
    // If we reach here, the login details were incorrect.
    header("Location: index.php?error=1");
    exit();

} else {
    // Redirect if someone tries to access this file directly.
    header("Location: index.php");
    exit();
}
?>