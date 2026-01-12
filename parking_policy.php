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
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Parking Policy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } @media print { .no-print { display: none; } } </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-8 my-8 bg-white shadow-lg rounded-lg max-w-4xl">
        <div class="flex justify-between items-start no-print mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Parking Policy</h1>
            <div class="flex space-x-2">
                <button onclick="window.close()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all font-semibold text-sm">Close</button>
                <button onclick="window.print()" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm">Print Document</button>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-6">Last Updated: January 2025</p>

        <div class="space-y-6 text-gray-700">
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">1. Resident Parking</h2>
                <p>Each apartment is assigned one (1) designated parking spot. A parking permit, available from the management office, must be displayed at all times. Vehicles without a permit are subject to towing at the owner's expense.</p>
            </section>
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">2. Guest Parking</h2>
                <p>Guest parking is available in the designated 'VISITOR' spots only. These spots are first-come, first-served and are intended for short-term use (maximum 48 hours). Residents may not park in visitor spots.</p>
            </section>
             <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">3. Prohibited Vehicles</h2>
                <p>Commercial vehicles, trailers, boats, and non-operational vehicles are not permitted to be parked on the property at any time.</p>
            </section>
        </div>
    </main>
</body>
</html>

