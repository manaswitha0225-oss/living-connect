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
    <title>Community Rules & Regulations</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } @media print { .no-print { display: none; } } </style>
</head>
<body class="bg-gray-100">
    <main class="container mx-auto p-8 my-8 bg-white shadow-lg rounded-lg max-w-4xl">
        <div class="flex justify-between items-start no-print mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Community Rules & Regulations</h1>
            <div class="flex space-x-2">
                 <button onclick="window.close()" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 transition-all font-semibold text-sm">Close</button>
                <button onclick="window.print()" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-all font-semibold text-sm">Print Document</button>
            </div>
        </div>
        <p class="text-sm text-gray-500 mb-6">Last Updated: July 2025</p>
        
        <div class="space-y-6 text-gray-700">
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">1. General Conduct</h2>
                <p>All residents and guests are expected to conduct themselves in a manner that is respectful of their neighbors and does not interfere with the peaceful enjoyment of the community. Harassment or disruptive behavior will not be tolerated.</p>
            </section>
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">2. Noise Policy</h2>
                <p>Quiet hours are from 10:00 PM to 8:00 AM daily. During these hours, please keep noise from televisions, music, and gatherings to a minimum. Excessive noise at any time is prohibited.</p>
            </section>
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">3. Trash Disposal</h2>
                <p>All household trash must be bagged, tied securely, and placed inside the designated trash receptacles. Do not leave trash bags in hallways, on balconies, or outside the dumpsters. Large items (e.g., furniture) must be disposed of off-site by the resident.</p>
            </section>
            <section>
                <h2 class="text-xl font-semibold mb-2 border-b pb-2">4. Balconies & Patios</h2>
                <p>Balconies and patios must be kept clean and tidy. They may not be used for storage of personal items, trash, or laundry. Only appropriate outdoor furniture and plants are permitted.</p>
            </section>
        </div>
    </main>
</body>
</html>

