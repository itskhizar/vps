<?php
/**
 * Safe Admin Initializer Script
 * Run from CLI or web to ensure an initial admin account exists
 */
require_once __DIR__ . '/../include/functions.php';

$db = get_db();
$admin_user = 'admin';
$admin_pass = 'admin123';
$hash = password_hash($admin_pass, PASSWORD_DEFAULT);

$stmt = $db->prepare("INSERT INTO users (username, password, userid, userlevel, email, timestamp, parent_directory)
    VALUES (?, ?, '0', 9, 'admin@vprovideservices.com', UNIX_TIMESTAMP(), 'admin')
    ON DUPLICATE KEY UPDATE password = ?, userlevel = 9");
$stmt->execute([$admin_user, $hash, $hash]);

// Also ensure capital 'Admin' is set to userlevel 9 with valid hash
$stmt2 = $db->prepare("UPDATE users SET password = ?, userlevel = 9 WHERE username = 'Admin'");
$stmt2->execute([$hash]);

echo "Admin account initialized/updated successfully.\n";
echo "Username: admin (or Admin)\n";
echo "Password: admin123\n";
echo "Userlevel: 9\n";
