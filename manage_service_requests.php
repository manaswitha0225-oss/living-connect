<?php
session_start();
// Redirect if not logged in as an admin
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../php/db_connect.php';

$updateSuccessMessage = '';

// Check if a status update was just submitted
if (isset($_SESSION['update_success'])) {
    $updateSuccessMessage = $_SESSION['update_success'];
    unset($_SESSION['update_success']); // Clear the message so it doesn't show again on refresh
}


// Fetch all service requests from the database
$requests = [];
$sql = "SELECT id, user_name, apartment_number, subject, description, status, created_at FROM service_requests ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
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
    <title>Manage Service Requests - Admin</title>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manage Service Requests</h1>

        <!-- Status Update Success Message -->
        <?php if (!empty($updateSuccessMessage)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6">
                <p><?php echo $updateSuccessMessage; ?></p>
            </div>
        <?php endif; ?>
        
        <div class="bg-white p-8 rounded-lg shadow-lg overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-4 font-semibold text-gray-700">Resident</th>
                        <th class="py-3 px-4 font-semibold text-gray-700">Apartment</th>
                        <th class="py-3 px-4 font-semibold text-gray-700">Subject</th>
                        <th class="py-3 px-4 font-semibold text-gray-700">Status</th>
                        <th class="py-3 px-4 font-semibold text-gray-700">Submitted On</th>
                        <th class="py-3 px-4 font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">No service requests found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($requests as $request): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-4"><?php echo htmlspecialchars($request['user_name']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($request['apartment_number']); ?></td>
                                <td class="py-3 px-4"><?php echo htmlspecialchars($request['subject']); ?></td>
                                <td class="py-3 px-4">
                                    <span class="text-sm font-semibold px-3 py-1 rounded-full <?php 
                                        switch ($request['status']) {
                                            case 'Submitted': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'In Progress': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'Completed': echo 'bg-green-100 text-green-800'; break;
                                        }
                                    ?>">
                                        <?php echo htmlspecialchars($request['status']); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-600"><?php echo date('M d, Y', strtotime($request['created_at'])); ?></td>
                                <td class="py-3 px-4">
                                    <form action="update_request_status.php" method="POST" class="flex items-center space-x-2">
                                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                                        <select name="status" class="border rounded-md p-1 text-sm">
                                            <option value="Submitted" <?php if ($request['status'] == 'Submitted') echo 'selected'; ?>>Submitted</option>
                                            <option value="In Progress" <?php if ($request['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                            <option value="Completed" <?php if ($request['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                        </select>
                                        <button type="submit" class="bg-indigo-500 text-white text-xs px-3 py-1 rounded-md hover:bg-indigo-600">Update</button>
                                    </form>
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

