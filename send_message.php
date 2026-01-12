<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['loggedin'])) {
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $userName = $_SESSION['user_name'];
        $role = 'resident'; // Set the role for residents

        $stmt = $conn->prepare("INSERT INTO chat_messages (user_name, message, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $userName, $message, $role);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Message is empty']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Not logged in or invalid request']);
}
?>

