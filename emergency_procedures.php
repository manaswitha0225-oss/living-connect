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
    <title>Emergency Procedures</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } @media print { .no-print { display: none; } } </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-8 my-8 bg-white shadow-lg rounded-lg max-w-4xl">
        <div class="flex justify-between items-start no-print mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Emergency Procedures</h1>
            <div class="flex space-x-2">
                <button onclick="window.close()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all font-semibold text-sm">Close</button>
                <button onclick="window.print()" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm">Print Document</button>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-6">Last Updated: June 2024</p>

        <div class="space-y-6 text-gray-700">
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">1. In Case of Fire</h2>
                <p>If you see a fire, activate the nearest fire alarm pull station. Evacuate the building immediately using the stairwells; do not use the elevators. Once outside, move to the designated assembly point in the main parking lot.</p>
            </section>
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">2. Medical Emergency</h2>
                <p>For any medical emergency, call 108 immediately. Be prepared to give your exact location, including your apartment number. After calling for help, please contact the front office if it is safe to do so.</p>
            </section>
             <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">3. Power Outage</h2>
                <p>In case of a power outage, emergency lighting will illuminate the hallways and stairwells. Avoid opening your refrigerator or freezer to preserve food. For updates, please check the announcements page on the resident portal via your mobile device.</p>
            </section>
        </div>
    </main>
</body>
</html>

