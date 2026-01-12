<?php
session_start();
// This is a protected page. Check if the admin is logged in.
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php"); 
    exit;
}

require_once '../php/db_connect.php';
$adminName = htmlspecialchars($_SESSION['admin_name']);
$statusMessage = '';

// Check for status messages from update script
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $statusMessage = 'Booking status updated successfully!';
    } else if ($_GET['status'] === 'error') {
        $statusMessage = 'Error updating booking status.';
    }
}


// Fetch all booking requests, newest first
$bookings = [];
$sql = "SELECT id, user_name, amenity, booking_date, time_slot, status, created_at FROM amenity_bookings ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-gray-100">

    <!-- Admin Header -->
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="dashboard.php" class="text-xl font-bold text-indigo-600"><i class="fas fa-user-shield mr-2"></i>Admin Dashboard</a>
            <div class="flex items-center space-x-4">
                <span class="text-gray-700 hidden sm:block">Welcome, <?php echo $adminName; ?>!</span>
                <a href="logout.php" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 text-sm font-semibold"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        </nav>
    </header>

    <!-- Admin Main Content -->
    <main class="container mx-auto px-6 py-12">
        <a href="dashboard.php" class="text-indigo-600 hover:underline mb-6 inline-block">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manage Amenity Bookings</h1>

        <?php if ($statusMessage): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                <p><?php echo $statusMessage; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white p-8 rounded-lg shadow-lg overflow-x-auto">
            <h2 class="text-2xl font-semibold mb-6">All Booking Requests</h2>
            <table class="min-w-full bg-white">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Resident</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Amenity</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Date & Time</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Status</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if (empty($bookings)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-6 text-gray-500">No booking requests found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr class="border-b">
                                <td class="py-4 px-4"><?php echo htmlspecialchars($booking['user_name']); ?></td>
                                <td class="py-4 px-4"><?php echo htmlspecialchars($booking['amenity']); ?></td>
                                <td class="py-4 px-4"><?php echo date('M d, Y', strtotime($booking['booking_date'])) . ' @ ' . htmlspecialchars($booking['time_slot']); ?></td>
                                <td class="py-4 px-4">
                                    <?php
                                        $status = htmlspecialchars($booking['status']);
                                        $color = 'gray';
                                        if ($status == 'Approved') $color = 'green';
                                        if ($status == 'Rejected') $color = 'red';
                                        if ($status == 'Pending') $color = 'yellow';
                                    ?>
                                    <span class="inline-block rounded-full px-3 py-1 text-sm font-semibold text-<?php echo $color; ?>-800 bg-<?php echo $color; ?>-200"><?php echo $status; ?></span>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($booking['status'] === 'Pending'): ?>
                                        <form action="update_booking_status.php" method="POST" class="inline-flex space-x-2">
                                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                                            <button type="submit" name="status" value="Approved" class="px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-md hover:bg-green-600">Approve</button>
                                            <button type="submit" name="status" value="Rejected" class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-md hover:bg-red-600">Reject</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-sm">Managed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>

