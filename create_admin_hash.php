<?php
// --- IMPORTANT ---
// 1. Change the password below to the password you want to use for your admin account.
// 2. Upload this file to your main htdocs folder.
// 3. Visit yoursite.com/create_admin_hash.php in your browser.
// 4. Copy the long string of text it displays.
// 5. DELETE this file from your server immediately.

$password = '123456'; // <-- CHANGE THIS to your desired secure password

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

echo "<h3>Your Secure Hashed Password:</h3>";
echo "<p>Copy the entire line below and paste it into the SQL command in the next step:</p>";
echo "<hr>";
echo "<code>" . htmlspecialchars($hashed_password) . "</code>";
?>
