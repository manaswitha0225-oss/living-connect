<?php
session_start();
// Protect the page: check if admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../php/db_connect.php';

// Handle form submission for adding a new announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_announcement'])) {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $sql = "INSERT INTO announcements (title, content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        // Redirect to the same page to prevent form resubmission
        header("Location: manage_announcements.php?status=success");
        exit();
    }
}

// Handle deletion of an announcement
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_announcement'])) {
    $id = intval($_POST['announcement_id']);
    $sql = "DELETE FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: manage_announcements.php?status=deleted");
    exit();
}


// Fetch all announcements from the database to display
$announcements = [];
$sql = "SELECT id, title, content, created_at FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="dashboard.php" class="text-xl font-bold text-indigo-600"><i class="fas fa-user-shield mr-2"></i>Admin Dashboard</a>
            <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm font-semibold"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-12">
        <a href="dashboard.php" class="text-indigo-600 hover:underline mb-6 inline-block">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manage Announcements</h1>

        <!-- Add New Announcement Form -->
        <div class="bg-white p-8 rounded-lg shadow-lg mb-12">
            <h2 class="text-2xl font-semibold mb-6">Add New Announcement</h2>
            <form action="manage_announcements.php" method="POST">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-semibold mb-2">Title</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="mb-6">
                    <label for="content" class="block text-gray-700 font-semibold mb-2">Content</label>
                    <textarea name="content" id="content" rows="5" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required></textarea>
                </div>
                <button type="submit" name="add_announcement" class="bg-green-500 text-white py-3 px-6 rounded-md hover:bg-green-600 transition-all font-semibold">Post Announcement</button>
            </form>
        </div>

        <!-- List of Existing Announcements -->
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-6">Existing Announcements</h2>
            <div class="space-y-4">
                <?php if (empty($announcements)): ?>
                    <p class="text-gray-500">No announcements have been posted yet.</p>
                <?php else: ?>
                    <?php foreach ($announcements as $announcement): ?>
                        <div class="border p-4 rounded-md bg-gray-50 flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-semibold text-gray-800"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                                <p class="text-gray-600 mt-2"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                                <p class="text-xs text-gray-400 mt-2">Posted on: <?php echo date('F j, Y, g:i a', strtotime($announcement['created_at'])); ?></p>
                            </div>
                            <form action="manage_announcements.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                                <button type="submit" name="delete_announcement" class="text-red-500 hover:text-red-700 ml-4">
                                    <i class="fas fa-trash-alt text-lg"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </main>
</body>
</html>