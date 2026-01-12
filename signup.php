<?php
// This script handles the user registration process.
// NOTE: This version does NOT automatically log the user in.

// Include the database connection file.
require_once 'db_connect.php';

// Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Data Collection & Sanitization ---
    $full_name = trim($_POST['signup-name']);
    $email = trim($_POST['signup-email']);
    $apartment_number = trim($_POST['signup-apt']);
    $password = trim($_POST['signup-password']);

    // --- Validation ---
    if (empty($full_name) || empty($email) || empty($apartment_number) || empty($password)) {
        die("Please fill out all fields. <a href='../index.html'>Go back</a>");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format. <a href='../index.html'>Go back</a>");
    }

    // --- Check if User Already Exists ---
    $sql_check = "SELECT id FROM users WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        die("An account with this email already exists. <a href='../index.html'>Try logging in</a>");
    }
    $stmt_check->close();

    // --- Password Hashing ---
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // --- Insert New User into Database ---
    $sql_insert = "INSERT INTO users (full_name, email, apartment_number, password_hash) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssss", $full_name, $email, $apartment_number, $password_hash);

    if ($stmt_insert->execute()) {
        // --- MODIFICATION ---
        // Signup was successful. We are no longer automatically logging the user in.
        // Instead, we redirect them to the homepage with a success parameter in the URL.
        
        // This will redirect to: yourdomain.com/index.html?signup=success
        header("Location: ../index.html?signup=success");
        exit();
    } else {
        // For a live site, you would log this error instead of showing it to the user.
        error_log("Signup Error: " . $stmt_insert->error);
        die("Something went wrong with the registration. Please try again later.");
    }

    // Close the statement and the database connection.
    $stmt_insert->close();
    $conn->close();
}
?>

