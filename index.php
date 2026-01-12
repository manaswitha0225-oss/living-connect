<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Living Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold text-center text-indigo-600 mb-2">Admin Panel</h1>
        <p class="text-center text-gray-500 mb-6">Living Connect Management</p>

        <!-- This PHP block will only show the error when the URL has ?error=1 -->
        <?php
            if (isset($_GET['error'])) {
                echo '<p class="bg-red-100 text-red-700 p-3 rounded-md text-center mb-4">Invalid email or password.</p>';
            }
        ?>
        
        <form action="auth.php" method="POST">
            <div class="mb-4">
                <label for="login-email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input type="email" name="login-email" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="admin@example.com" required>
            </div>
            <div class="mb-6">
                <label for="login-password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" name="login-password" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 transition-all font-semibold">Log In</button>
        </form>
    </div>
</body>
</html>