<?php
// Replace 'your_admin_password_here' with the actual admin password you want to hash
$adminPassword = 'your_admin_password_here';

// Hash the admin password using bcrypt
$hashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT);

// Display the hashed password (for reference)
echo "Hashed Password: " . $hashedPassword . "\n";
?>
