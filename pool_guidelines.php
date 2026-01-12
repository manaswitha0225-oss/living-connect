<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pool Usage Guidelines</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } @media print { .no-print { display: none; } } </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-8 my-8 bg-white shadow-lg rounded-lg max-w-4xl">
        <div class="flex justify-between items-start no-print mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Pool Usage Guidelines</h1>
            <div class="flex space-x-2">
                <button onclick="window.close()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all font-semibold text-sm">Close</button>
                <button onclick="window.print()" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm">Print Document</button>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-6">Last Updated: May 2025</p>

        <div class="space-y-6 text-gray-700">
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">1. Pool Hours</h2>
                <p>The pool is open daily from 9:00 AM to 9:00 PM during the summer season. All swimmers must exit the pool area by 9:00 PM sharp.</p>
            </section>
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">2. Safety Rules</h2>
                <ul class="list-disc list-inside space-y-1">
                    <li>No running, pushing, or rough play is permitted in the pool area.</li>
                    <li>No glass containers of any kind are allowed.</li>
                    <li>Children under the age of 14 must be accompanied by an adult resident at all times.</li>
                    <li>No diving is permitted in the shallow end of the pool.</li>
                </ul>
            </section>
             <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">3. Guest Policy</h2>
                <p>Residents may bring a maximum of two (2) guests to the pool. Residents are responsible for the conduct of their guests and must remain with them at all times.</p>
            </section>
        </div>
    </main>
</body>
</html>

