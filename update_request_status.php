<?php
session_start();
// Redirect if not logged in as an admin
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../php/db_connect.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['request_id']) && isset($_POST['status'])) {
    $requestId = $_POST['request_id'];
    $newStatus = $_POST['status'];

    // Validate the status to ensure it's one of the allowed values
    $allowedStatuses = ['Submitted', 'In Progress', 'Completed'];
    if (in_array($newStatus, $allowedStatuses)) {
        
        // Prepare and execute the update statement
        $sql = "UPDATE service_requests SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $requestId);

        if ($stmt->execute()) {
            // Set a success message in the session
            $_SESSION['update_success'] = "Successfully updated the status for request ID #{$requestId}.";
        } else {
            // Optional: Handle potential update errors
            $_SESSION['update_success'] = "Error: Could not update the status.";
        }
    } else {
        // Handle invalid status value
         $_SESSION['update_success'] = "Error: Invalid status value provided.";
    }

    // Redirect back to the management page
    header("Location: manage_service_requests.php");
    exit();

} else {
    // If accessed directly without POST data, just redirect back
    header("Location: manage_service_requests.php");
    exit();
}
?>

