<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

require_once 'php/db_connect.php';
$userName = htmlspecialchars($_SESSION['user_name']);

// Fetch all announcements from the database, ordering by newest first
$announcements = [];
$sql = "SELECT title, content, created_at FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $announcements[] = $row;
    }
}

// Define an array of icons and colors to cycle through
$styles = [
    ['icon' => 'fa-bullhorn', 'color' => 'indigo'],
    ['icon' => 'fa-wrench', 'color' => 'sky'],
    ['icon' => 'fa-tree', 'color' => 'emerald'],
    ['icon' => 'fa-star', 'color' => 'amber'],
    ['icon' => 'fa-info-circle', 'color' => 'rose'],
];
$styleCount = count($styles);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Living Connect</title>
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
                <a href="php/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-all duration-300 text-sm font-semibold shadow-sm hover:shadow-md"><i class="fas fa-sign-out-alt mr-1"></i>Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <a href="portal_home.php" class="text-indigo-600 hover:text-indigo-800 text-sm mb-6 inline-flex items-center"><i class="fas fa-arrow-left mr-2"></i>Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Community Announcements</h1>
        
        <div class="space-y-6 max-w-4xl mx-auto">
            <?php if (empty($announcements)): ?>
                <div class="bg-white p-8 rounded-xl shadow-md text-center">
                    <i class="fas fa-info-circle text-4xl text-slate-400 mb-4"></i>
                    <p class="text-slate-500 text-lg">There are no announcements at this time.</p>
                    <p class="text-slate-400 mt-1">Please check back later for updates from the community management.</p>
                </div>
            <?php else: ?>
                <?php foreach ($announcements as $index => $announcement): 
                    $style = $styles[$index % $styleCount];
                    $borderColor = "border-{$style['color']}-500";
                    $iconColor = "text-{$style['color']}-500";
                    $bgColor = "bg-{$style['color']}-50";
                ?>
                    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 <?php echo $borderColor; ?> flex space-x-4 items-start">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full <?php echo $bgColor; ?> flex items-center justify-center">
                            <i class="fas <?php echo $style['icon']; ?> text-2xl <?php echo $iconColor; ?>"></i>
                        </div>
                        <div class="flex-grow">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center mb-2">
                                <h2 class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($announcement['title']); ?></h2>
                                <span class="text-sm text-slate-500 mt-1 sm:mt-0 flex-shrink-0">
                                    <i class="far fa-calendar-alt mr-1.5"></i>Posted on: <?php echo date('F d, Y', strtotime($announcement['created_at'])); ?>
                                </span>
                            </div>
                            <p class="text-slate-600 leading-relaxed"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>

