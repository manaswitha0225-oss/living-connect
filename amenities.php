<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

require_once 'php/db_connect.php';
$userName = htmlspecialchars($_SESSION['user_name']);
$userId = $_SESSION['user_id'];
$successMessage = '';

// Handle form submission for new booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['amenity'])) {
    $amenity = $_POST['amenity'];
    $booking_date = $_POST['date'];
    $time_slot = $_POST['time'];

    // Basic validation
    if (!empty($amenity) && !empty($booking_date) && !empty($time_slot)) {
        $sql = "INSERT INTO amenity_bookings (user_id, user_name, amenity, booking_date, time_slot) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $userId, $userName, $amenity, $booking_date, $time_slot);
        
        if ($stmt->execute()) {
            $successMessage = "Your booking request for the {$amenity} has been submitted successfully! You can track its status below.";
        } else {
            $successMessage = "Error: Could not submit your booking request. Please try again.";
        }
    }
}

// Fetch user's booking history
$bookings = [];
$sql = "SELECT amenity, booking_date, time_slot, status, created_at FROM amenity_bookings WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
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
    <title>Book Amenities - Living Connect</title>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Book an Amenity</h1>
        
        <!-- Booking Form -->
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl font-semibold mb-6">New Reservation</h2>
            
            <?php if ($successMessage): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
                    <p><?php echo $successMessage; ?></p>
                </div>
            <?php endif; ?>

            <form action="amenities.php" method="POST">
                <div class="mb-4">
                    <label for="amenity" class="block text-gray-700 font-semibold mb-2">Select Amenity</label>
                    <select id="amenity" name="amenity" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <option value="Clubhouse">Clubhouse</option>
                        <option value="Tennis Court">Tennis Court</option>
                        <option value="Rooftop Terrace">Rooftop Terrace</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="date" class="block text-gray-700 font-semibold mb-2">Date</label>
                    <input type="date" id="date" name="date" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                </div>
                <div class="mb-6">
                    <label for="time" class="block text-gray-700 font-semibold mb-2">Time Slot</label>
                    <select id="time" name="time" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        <option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM</option>
                        <option value="12:00 PM - 02:00 PM">12:00 PM - 02:00 PM</option>
                        <option value="02:00 PM - 04:00 PM">02:00 PM - 04:00 PM</option>
                        <option value="04:00 PM - 06:00 PM">04:00 PM - 06:00 PM</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-green-500 text-white py-3 rounded-md hover:bg-green-600 transition-all font-semibold">Submit Booking Request</button>
            </form>
        </div>

        <!-- Booking History -->
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-4xl mx-auto">
            <h2 class="text-2xl font-semibold mb-6">My Booking History</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Amenity</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Date & Time</th>
                            <th class="text-left py-3 px-4 uppercase font-semibold text-sm text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php if (empty($bookings)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-6 text-gray-500">You have no booking history.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr class="border-b">
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
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>

