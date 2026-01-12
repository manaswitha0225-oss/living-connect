<?php
session_start();
// This is a protected page. Check if the admin is logged in.
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../php/db_connect.php';
$adminName = htmlspecialchars($_SESSION['admin_name']);

// Handle form submission for adding a new event
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_event'])) {
    $title = trim($_POST['title']);
    $location = trim($_POST['location']);
    $event_date = trim($_POST['event_date']);
    $start_time = trim($_POST['start_time']);
    $end_time = trim($_POST['end_time']);

    if (!empty($title) && !empty($location) && !empty($event_date) && !empty($start_time) && !empty($end_time)) {
        $sql = "INSERT INTO events (title, location, event_date, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $title, $location, $event_date, $start_time, $end_time);
        
        if ($stmt->execute()) {
            header("Location: manage_events.php?status=success");
            exit();
        } else {
            header("Location: manage_events.php?status=error");
            exit();
        }
    }
}

// Handle deletion of an event
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: manage_events.php?status=deleted");
        exit();
    }
}


// Fetch all existing events from the database
$events = [];
$sql = "SELECT id, title, location, event_date, start_time, end_time FROM events ORDER BY event_date DESC, start_time DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100">

    <!-- Admin Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="dashboard.php" class="text-xl font-bold text-indigo-600"><i class="fas fa-user-shield mr-2"></i>Admin Dashboard</a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $adminName; ?>!</span>
                <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-semibold shadow-sm"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <a href="dashboard.php" class="text-indigo-600 hover:text-indigo-800 text-sm mb-6 inline-flex items-center"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Manage Community Events</h1>
        
        <!-- Add New Event Form -->
        <div class="bg-white p-6 rounded-xl shadow-md mb-8 max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold text-slate-700 mb-4">Add New Event</h2>
            <?php 
                if (isset($_GET['status']) && $_GET['status'] == 'success') echo '<p class="bg-green-100 text-green-700 p-3 rounded-md text-center mb-4">Event added successfully!</p>';
                if (isset($_GET['status']) && $_GET['status'] == 'deleted') echo '<p class="bg-yellow-100 text-yellow-700 p-3 rounded-md text-center mb-4">Event has been deleted.</p>';
            ?>
            <form action="manage_events.php" method="POST" class="space-y-4">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-600 mb-1">Event Title</label>
                    <input type="text" name="title" id="title" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-600 mb-1">Location</label>
                    <input type="text" name="location" id="location" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-slate-600 mb-1">Date</label>
                        <input type="date" name="event_date" id="event_date" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                     <div>
                        <label for="start_time" class="block text-sm font-medium text-slate-600 mb-1">Start Time</label>
                        <input type="time" name="start_time" id="start_time" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                     <div>
                        <label for="end_time" class="block text-sm font-medium text-slate-600 mb-1">End Time</label>
                        <input type="time" name="end_time" id="end_time" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                </div>
                <div>
                    <button type="submit" name="add_event" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-semibold">Post Event</button>
                </div>
            </form>
        </div>

        <!-- Existing Events List -->
        <div class="bg-white p-6 rounded-xl shadow-md max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold text-slate-700 mb-4">Existing Events</h2>
            <div class="space-y-4">
                <?php if (empty($events)): ?>
                    <p class="text-slate-500 text-center py-4">No events have been created yet.</p>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                        <div class="border border-slate-200 p-4 rounded-lg flex flex-col sm:flex-row justify-between sm:items-center">
                            <div>
                                <h3 class="font-bold text-lg text-slate-800"><?php echo htmlspecialchars($event['title']); ?></h3>
                                <p class="text-sm text-slate-500">
                                    <i class="fas fa-calendar-alt mr-1"></i> <?php echo date('D, F j, Y', strtotime($event['event_date'])); ?> 
                                    <span class="mx-2">|</span>
                                    <i class="fas fa-clock mr-1"></i> <?php echo date('g:i A', strtotime($event['start_time'])) . ' - ' . date('g:i A', strtotime($event['end_time'])); ?>
                                </p>
                                <p class="text-sm text-slate-500">
                                    <i class="fas fa-map-marker-alt mr-1"></i> <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                            </div>
                            <a href="manage_events.php?delete_id=<?php echo $event['id']; ?>" onclick="return confirm('Are you sure you want to delete this event?');" class="mt-3 sm:mt-0 text-red-500 hover:text-red-700 font-semibold text-sm">
                                <i class="fas fa-trash-alt mr-1"></i> Delete
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>

