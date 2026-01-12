<?php
require_once 'db_connect.php';

// Select the role along with other message details
$result = $conn->query("SELECT id, user_name, message, timestamp, role FROM chat_messages ORDER BY timestamp ASC");

$messages = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format the timestamp for display
        $row['timestamp'] = date('h:i A', strtotime($row['timestamp']));
        $messages[] = $row;
    }
}

// Set the content type to JSON and output the messages
header('Content-Type: application/json');
echo json_encode($messages);
?>

