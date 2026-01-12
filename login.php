<?php
// This script handles the user login process.

// A session is used to keep track of a user being logged in across different pages.
// We must start it on any page that needs login information.
session_start();

// Include the database connection file
require_once 'db_connect.php';

// Check if the form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the data from the form and trim whitespace
    $email = trim($_POST['login-email']);
    $password = trim($_POST['login-password']);
    
    // Simple validation
    if (empty($email) || empty($password)) {
        die("Please enter both email and password. <a href='../index.html'>Try again</a>");
    }

    // --- Find the User ---
    // Prepare a statement to find the user by their email address
    $sql = "SELECT id, full_name, password_hash FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User was found, now fetch their data
        $user = $result->fetch_assoc();
        
        // --- Verify the Password ---
        // Use password_verify() to compare the submitted password with the hashed password from the database
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct!
            
            // --- Start the Session ---
            // Store user data in session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];

            // Redirect the user to their personal portal page
            header("Location: ../portal_home.php"); 
            exit(); // Important to stop the script from running after redirection
        } else {
            // Incorrect password
            echo "Invalid email or password. <a href='../index.html'>Try again</a>";
        }
    } else {
        // No user found with that email address
        echo "Invalid email or password. <a href='../index.html'>Try again</a>";
    }

    // Close the database connections
    $stmt->close();
    $conn->close();
}
?>

