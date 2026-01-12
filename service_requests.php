<?php
session_start();
// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}

require_once 'php/db_connect.php';

$userId = $_SESSION['user_id'];
$userName = htmlspecialchars($_SESSION['user_name']);
// Assuming apartment_number is stored in session from login
$apartmentNumber = isset($_SESSION['apartment_number']) ? htmlspecialchars($_SESSION['apartment_number']) : 'N/A';

$successMessage = '';

// Handle form submission for new service requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_request'])) {
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);

    if (!empty($subject) && !empty($description)) {
        $sql = "INSERT INTO service_requests (user_id, user_name, apartment_number, subject, description) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $userId, $userName, $apartmentNumber, $subject, $description);

        if ($stmt->execute()) {
            $successMessage = "Your service request has been submitted successfully!";
        } else {
            // Optional: Add an error message if submission fails
            $successMessage = "Error: Could not submit your request. Please try again later.";
        }
    }
}

// Fetch existing service requests for the current user
$requests = [];
$sql = "SELECT subject, description, status, created_at, updated_at FROM service_requests WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Requests - Living Connect</title>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Service Requests</h1>

        <!-- Success Message -->
        <?php if (!empty($successMessage)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6">
                <p><?php echo $successMessage; ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- New Request Form -->
            <div class="lg:col-span-1 bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-6">Submit a New Request</h2>
                <form action="service_requests.php" method="POST">
                    <div class="mb-4">
                        <label for="subject" class="block text-gray-700 font-semibold mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g., Leaky Faucet" required>
                    </div>
                    <div class="mb-6">
                        <label for="description" class="block text-gray-700 font-semibold mb-2">Description</label>
                        <textarea id="description" name="description" rows="5" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Please provide details about the issue." required></textarea>
                    </div>
                    <button type="submit" name="submit_request" class="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition-all font-semibold">Submit Request</button>
                </form>
            </div>

            <!-- Previous Requests -->
            <div class="lg:col-span-2 bg-white p-8 rounded-lg shadow-lg">
                <h2 class="text-2xl font-semibold mb-6">Your Request History</h2>
                <div class="space-y-6">
                    <?php if (empty($requests)): ?>
                        <p class="text-gray-500 text-center py-4">You have not submitted any service requests yet.</p>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                            <div class="border rounded-md p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-lg text-gray-800"><?php echo htmlspecialchars($request['subject']); ?></h3>
                                        <p class="text-sm text-gray-500">Submitted: <?php echo date('M d, Y, h:i A', strtotime($request['created_at'])); ?></p>
                                    </div>
                                    <span class="text-sm font-semibold px-3 py-1 rounded-full <?php 
                                        switch ($request['status']) {
                                            case 'Submitted': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'In Progress': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'Completed': echo 'bg-green-100 text-green-800'; break;
                                        }
                                    ?>">
                                        <?php echo htmlspecialchars($request['status']); ?>
                                    </span>
                                </div>
                                <p class="text-gray-600 mt-2"><?php echo nl2br(htmlspecialchars($request['description'])); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

