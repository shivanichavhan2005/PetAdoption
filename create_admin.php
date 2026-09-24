<?php
// =========================================
// Run this file ONCE in the browser to create the admin account.
// http://localhost/PetAdoption/create_admin.php
// Then DELETE this file (or block access to it).
// =========================================
require "dbconnect.php";

$name = "Admin";
$email = "admin@petadopt.com";
$plain_password = "admin123";
$hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);

// Avoid creating a duplicate admin if this is run more than once
$check = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "Admin account already exists. You can delete this file now.";
} else {
    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt->bind_param("sss", $name, $email, $hashed_password);
    if ($stmt->execute()) {
        echo "Admin account created successfully!<br>";
        echo "Email: $email<br>";
        echo "Password: $plain_password<br>";
        echo "<strong>Delete this file (create_admin.php) now for security.</strong>";
    } else {
        echo "Error creating admin: " . $conn->error;
    }
    $stmt->close();
}
$check->close();
$conn->close();
?>
