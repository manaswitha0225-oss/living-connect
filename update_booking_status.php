<?php
session_start();
// This is a protected script. Check if the admin is logged in.
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    // If not logged in, you can redirect or just exit.
    // For a script like this, exiting is fine as it shouldn't be accessed directly.
    exit('Access Denied');
}

require_once '../php/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['status'])) {
    
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['status'];

    // Validate the status to be one of the allowed values
    if ($new_status === 'Approved' || $new_status === 'Rejected') {
        
        $sql = "UPDATE amenity_bookings SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $booking_id);

        if ($stmt->execute()) {
            // Success, redirect back with a success message
            header("Location: manage_bookings.php?status=success");
            exit();
        } else {
            // Error, redirect back with an error message
            header("Location: manage_bookings.php?status=error");
            exit();
        }
    } else {
        // Invalid status value, redirect back with an error
        header("Location: manage_bookings.php?status=error");
        exit();
    }
} else {
    // If accessed directly or without the proper POST data, just redirect
    header("Location: manage_bookings.php");
    exit();
}
?>

