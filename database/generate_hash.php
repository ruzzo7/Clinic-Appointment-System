<?php
/**
 * Generate Password Hash for admin123
 */

$password = 'admin123';
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

echo "Password: $password\n";
echo "Hash: $hash\n";
echo "\n";
echo "Copy this hash to your SQL file:\n";
echo $hash;
