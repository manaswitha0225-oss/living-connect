<?php
// This script handles the user logout process.

// First, we must start the session, just like when logging in.
session_start();

// --- End the Session ---
// Unset all of the session variables.
$_SESSION = array();

// Destroy the session completely.
session_destroy();

// --- Redirect to Homepage ---
// After logging out, send the user back to the main page.
header("Location: ../index.html");
exit; // Ensure no more code is executed after the redirect.
?>


