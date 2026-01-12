<?php
// Start the session to access session variables.
session_start();

// Check if the user is logged in. If not, redirect them to the homepage.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

// Get the user's name from the session variable.
$userName = isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Resident';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Portal - Living Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Portal Header -->
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="portal_home.php" class="text-2xl font-bold text-indigo-600">
                <i class="fas fa-city mr-2"></i>Living Connect Portal
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $userName; ?>!</span>
                <a href="php/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition-all duration-300 text-sm font-semibold">
                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                </a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-12">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Your Community Dashboard</h1>

        <!-- Dashboard Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Announcements Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-bullhorn text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Announcements</h2>
                </div>
                <p class="text-gray-600 mb-4">View the latest news and updates from the community management.</p>
                <a href="announcements.php" class="font-semibold text-indigo-600 hover:underline">View Announcements &rarr;</a>
            </div>

            <!-- Book Amenities Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Book Amenities</h2>
                </div>
                <p class="text-gray-600 mb-4">Reserve the clubhouse, pool area, or tennis court for your events.</p>
                <a href="amenities.php" class="font-semibold text-green-600 hover:underline">Make a Reservation &rarr;</a>
            </div>

            <!-- Service Requests Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-tools text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Service Requests</h2>
                </div>
                <p class="text-gray-600 mb-4">Submit and track maintenance requests for your apartment.</p>
                <a href="service_requests.php" class="font-semibold text-yellow-600 hover:underline">Submit a Request &rarr;</a>
            </div>

            <!-- Community Forums Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Community Forums</h2>
                </div>
                <p class="text-gray-600 mb-4">Connect with your neighbors, discuss topics, and share information.</p>
                <a href="forums.php" class="font-semibold text-blue-600 hover:underline">Join the Discussion &rarr;</a>
            </div>

            <!-- Events Calendar Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-glass-cheers text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Events Calendar</h2>
                </div>
                <p class="text-gray-600 mb-4">Check out upcoming community events like parties and workshops.</p>
                <a href="events.php" class="font-semibold text-purple-600 hover:underline">View Events &rarr;</a>
            </div>

            <!-- Documents & Rules Card -->
            <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800">Documents & Rules</h2>
                </div>
                <p class="text-gray-600 mb-4">Access important community documents, rules, and guidelines.</p>
                <a href="documents.php" class="font-semibold text-gray-600 hover:underline">Access Files &rarr;</a>
            </div>

        </div>
    </main>

</body>
</html>

