<?php
session_start();
// This is a protected page. Check if the admin is logged in.
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php"); // Redirect to admin login page
    exit;
}
$adminName = htmlspecialchars($_SESSION['admin_name']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Living Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-100">

    <!-- Admin Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="dashboard.php" class="text-xl font-bold text-indigo-600">
                <i class="fas fa-user-shield mr-2"></i>Admin Dashboard
            </a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $adminName; ?>!</span>
                <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 text-sm font-semibold shadow-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i>Logout
                </a>
            </div>
        </nav>
    </header>

    <!-- Admin Main Content -->
    <main class="container mx-auto px-4 sm:px-6 py-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-8">Management Panel</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <a href="manage_announcements.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all">
                <i class="fas fa-bullhorn text-3xl text-indigo-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-800">Manage Announcements</h2>
                <p class="text-slate-600 mt-2 text-sm">Create, edit, and delete community announcements.</p>
            </a>
            
            <a href="manage_service_requests.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all">
                <i class="fas fa-tools text-3xl text-amber-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-800">Manage Service Requests</h2>
                <p class="text-slate-600 mt-2 text-sm">View and update resident maintenance requests.</p>
            </a>

            <a href="manage_users.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all">
                <i class="fas fa-users text-3xl text-emerald-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-800">Manage Users</h2>
                <p class="text-slate-600 mt-2 text-sm">View and manage all registered resident accounts.</p>
            </a>

            <a href="manage_bookings.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all">
                <i class="fas fa-calendar-check text-3xl text-sky-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-800">Manage Bookings</h2>
                <p class="text-slate-600 mt-2 text-sm">Approve or reject amenity booking requests.</p>
            </a>

            <a href="moderate_discussion.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all">
                <i class="fas fa-comments text-3xl text-violet-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-800">Moderate Chat</h2>
                <p class="text-slate-600 mt-2 text-sm">View the chat and delete inappropriate messages.</p>
            </a>

            <!-- CORRECTED CARD FOR EVENTS -->
            <a href="manage_events.php" class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition-all">
                <i class="fas fa-calendar-alt text-3xl text-rose-500 mb-4"></i>
                <h2 class="text-xl font-semibold text-slate-800">Manage Events</h2>
                <p class="text-slate-600 mt-2 text-sm">Create, update, and remove community events.</p>
            </a>

        </div>
    </main>

</body>
</html>

