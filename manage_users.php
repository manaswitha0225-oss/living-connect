<?php
session_start();
// Ensure admin is logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once '../php/db_connect.php';

// Fetch all users from the 'users' table
$users = [];
$sql = "SELECT id, full_name, email, apartment_number, registration_date FROM users ORDER BY registration_date DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin</title>
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
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Manage Resident Accounts</h1>

        <!-- Status Message Display -->
        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md mb-6">
                <p>User account has been deleted successfully.</p>
            </div>
        <?php endif; ?>

        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold mb-6">All Registered Users</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-3 px-4 text-left">Full Name</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Apartment #</th>
                            <th class="py-3 px-4 text-left">Registered On</th>
                            <th class="py-3 px-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php if (empty($users)): ?>
                            <tr><td colspan="5" class="py-4 px-4 text-center text-gray-500">No users found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="py-4 px-4 font-semibold"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                    <td class="py-4 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="py-4 px-4"><?php echo htmlspecialchars($user['apartment_number']); ?></td>
                                    <td class="py-4 px-4"><?php echo date('M d, Y', strtotime($user['registration_date'])); ?></td>
                                    <td class="py-4 px-4">
                                        <form action="delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                                <i class="fas fa-trash-alt mr-1"></i>Delete
                                            </button>
                                        </form>
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
