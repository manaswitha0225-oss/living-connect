<?php
session_start();
require_once '../php/db_connect.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

$adminName = htmlspecialchars($_SESSION['admin_name']);

// --- HANDLE ADMIN MESSAGE POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_message'])) {
    $message = trim($_POST['admin_message']);
    if (!empty($message)) {
        $role = 'admin'; // Set the role for admin messages
        
        $stmt = $conn->prepare("INSERT INTO chat_messages (user_name, message, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $adminName, $message, $role);
        
        if ($stmt->execute()) {
            header("Location: moderate_discussion.php?sent=success");
            exit;
        }
        $stmt->close();
    }
}


// --- SIMPLIFIED DELETION LOGIC ---
if (isset($_GET['delete_id'])) {
    $message_id_to_delete = $_GET['delete_id'];
    
    $delete_stmt = $conn->prepare("DELETE FROM chat_messages WHERE id = ?");
    $delete_stmt->bind_param("i", $message_id_to_delete);
    
    if ($delete_stmt->execute()) {
        header("Location: moderate_discussion.php?delete=success");
        exit;
    }
    $delete_stmt->close();
}

// Fetch all chat messages to display
$messages = [];
// Added the 'role' to the SELECT query
$result = $conn->query("SELECT id, user_name, message, timestamp, role FROM chat_messages ORDER BY timestamp DESC");
if ($result) {
    $messages = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderate Discussion - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="dashboard.php" class="text-xl font-bold text-indigo-600"><i class="fas fa-user-shield mr-2"></i>Admin Dashboard</a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $adminName; ?>!</span>
                <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm font-semibold"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-12">
        <a href="dashboard.php" class="text-indigo-600 hover:underline mb-6 inline-block">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Moderate Community Discussion</h1>
        
        <?php if(isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
        <div id="status-message" class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md">
            <p>Message deleted successfully.</p>
        </div>
        <?php endif; ?>
         <?php if(isset($_GET['sent']) && $_GET['sent'] == 'success'): ?>
        <div id="status-message" class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-md">
            <p>Your message has been posted.</p>
        </div>
        <?php endif; ?>

        <!-- NEW: Admin Chat Form -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-8">
            <h2 class="text-2xl font-semibold mb-4">Send a Message as Admin</h2>
            <form method="POST" action="moderate_discussion.php">
                <textarea name="admin_message" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" rows="3" placeholder="Type your message here..."></textarea>
                <button type="submit" class="mt-4 px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">Send Message</button>
            </form>
        </div>


        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-6">Chat History</h2>
            <div id="chat-history" class="space-y-4">
                <?php if (empty($messages)): ?>
                    <p class="text-gray-500 text-center py-4">No messages in the chat yet.</p>
                <?php else: ?>
                    <?php foreach ($messages as $msg): ?>
                        <?php 
                            // Check if the message is from an admin
                            $isAdminMessage = isset($msg['role']) && $msg['role'] === 'admin';
                            $highlightClass = $isAdminMessage ? 'bg-indigo-50 border-indigo-200' : 'border-gray-200';
                        ?>
                        <div class="flex justify-between items-start p-4 border rounded-md <?php echo $highlightClass; ?>">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <p class="font-semibold text-indigo-600"><?php echo htmlspecialchars($msg['user_name']); ?></p>
                                    <?php if ($isAdminMessage): ?>
                                        <span class="text-xs bg-indigo-500 text-white px-2 py-0.5 rounded-full">Admin</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-700 mt-1"><?php echo htmlspecialchars($msg['message']); ?></p>
                                <p class="text-xs text-gray-400 mt-2"><?php echo date('M d, Y g:i A', strtotime($msg['timestamp'])); ?></p>
                            </div>
                            <a href="moderate_discussion.php?delete_id=<?php echo $msg['id']; ?>" class="text-red-500 hover:text-red-700 text-lg" onclick="return confirm('Are you sure you want to delete this message?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide the success message
        const statusMessage = document.getElementById('status-message');
        if (statusMessage) {
            setTimeout(() => {
                statusMessage.style.transition = 'opacity 0.5s ease';
                statusMessage.style.opacity = '0';
                setTimeout(() => statusMessage.remove(), 500);
            }, 3000); // 3 seconds
        }
    </script>
</body>
</html>

