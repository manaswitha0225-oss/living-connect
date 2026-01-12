<?php
// --- Database Connection Details ---
// IMPORTANT: Replace the password placeholder with your actual
//            InfinityFree account (vPanel) password.

$servername = "sql300.infinityfree.com";
$username = "if0_39911482";
$password = "livingconnect"; // Use your main InfinityFree account password here.
$dbname = "if0_39911482_liveconnect";


// --- Create Connection ---
// The @ symbol suppresses the default PHP warning, allowing us to create a custom error message.
$conn = @new mysqli($servername, $username, $password, $dbname);

// --- Check Connection ---
// If the connect_error property is not null, it means there was an error.
if ($conn->connect_error) {
    // For a live site, you might want to log this error instead of showing it to the user.
    // For now, dying is fine for development.
    die("Connection failed: " . $conn->connect_error);
}

// If the script reaches this point, the connection was successful.
?>

