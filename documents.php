<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Resident';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents & Rules - Living Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="portal_home.php" class="text-2xl font-bold text-indigo-600"><i class="fas fa-city mr-2"></i>Living Connect Portal</a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $userName; ?>!</span>
                <a href="php/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all duration-300 text-sm font-semibold"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto px-6 py-12">
        <a href="portal_home.php" class="text-indigo-600 hover:underline mb-6 inline-block">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Community Documents & Rules</h1>

        <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
            <ul class="divide-y divide-gray-200">
                <!-- Document Item 1 -->
                <li class="py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-3xl text-indigo-500 mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Community Rules & Regulations</p>
                            <p class="text-sm text-gray-500">Updated: July 2025</p>
                        </div>
                    </div>
                    <a href="community_rules.php" target="_blank" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm"><i class="fas fa-eye mr-2"></i>View</a>
                </li>
                <!-- Document Item 2 -->
                <li class="py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-3xl text-indigo-500 mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Pool Usage Guidelines</p>
                            <p class="text-sm text-gray-500">Updated: May 2025</p>
                        </div>
                    </div>
                    <a href="pool_guidelines.php" target="_blank" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm"><i class="fas fa-eye mr-2"></i>View</a>
                </li>
                <!-- Document Item 3 -->
                <li class="py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-3xl text-indigo-500 mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Parking Policy</p>
                            <p class="text-sm text-gray-500">Updated: January 2025</p>
                        </div>
                    </div>
                    <a href="parking_policy.php" target="_blank" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm"><i class="fas fa-eye mr-2"></i>View</a>
                </li>
                 <!-- Document Item 4 -->
                 <li class="py-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-3xl text-indigo-500 mr-4"></i>
                        <div>
                            <p class="font-semibold text-gray-800">Emergency Procedures</p>
                            <p class="text-sm text-gray-500">Updated: June 2024</p>
                        </div>
                    </div>
                    <a href="emergency_procedures.php" target="_blank" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm"><i class="fas fa-eye mr-2"></i>View</a>
                </li>
            </ul>
        </div>
    </main>
</body>
</html>

