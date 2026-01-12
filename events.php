<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

require_once 'php/db_connect.php';
$userName = htmlspecialchars($_SESSION['user_name']);

// Fetch all events from the database, ordering by the event date
$events = [];
$sql = "SELECT title, location, event_date, start_time, end_time FROM events ORDER BY event_date ASC, start_time ASC";
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
    <title>Events Calendar - Living Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
    </style>
</head>
<body class="bg-slate-100">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="portal_home.php" class="text-2xl font-bold text-indigo-600"><i class="fas fa-city mr-2"></i>Living Connect</a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $userName; ?>!</span>
                <a href="php/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300 text-sm font-semibold shadow-sm"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <a href="portal_home.php" class="text-indigo-600 hover:text-indigo-800 text-sm mb-6 inline-flex items-center"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Upcoming Community Events</h1>
        
        <div class="bg-white rounded-xl shadow-md max-w-4xl mx-auto">
            <?php if (empty($events)): ?>
                <div class="p-8 text-center">
                    <i class="fas fa-calendar-times text-4xl text-slate-400 mb-4"></i>
                    <p class="text-slate-500 text-lg">There are no upcoming events scheduled at this time.</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-slate-200">
                    <?php foreach ($events as $event): ?>
                        <li class="p-6 flex items-start space-x-6">
                            <div class="text-center w-20 flex-shrink-0">
                                <p class="text-3xl font-bold text-indigo-600"><?php echo date('d', strtotime($event['event_date'])); ?></p>
                                <p class="text-sm text-slate-500 uppercase font-semibold"><?php echo date('M', strtotime($event['event_date'])); ?></p>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-xl font-semibold text-slate-800 mb-1"><?php echo htmlspecialchars($event['title']); ?></h2>
                                <p class="text-sm text-slate-500 mb-1">
                                    <i class="fas fa-map-marker-alt fa-fw mr-2 text-slate-400"></i>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                                <p class="text-sm text-slate-500">
                                    <i class="fas fa-clock fa-fw mr-2 text-slate-400"></i>
                                    <?php echo date('g:i A', strtotime($event['start_time'])); ?> - <?php echo date('g:i A', strtotime($event['end_time'])); ?>
                                </p>
                            </div>
                            <button class="bg-emerald-500 text-white px-4 py-1.5 rounded-full text-sm font-semibold hover:bg-emerald-600 transition-colors">RSVP</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

