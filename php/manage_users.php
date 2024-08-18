<?php
session_start();
require 'config.php';

check_role('Admin'); // Nur Admins dürfen Benutzer verwalten

// Benutzer auflisten
$users = $conn->query("SELECT id, name, email, role FROM users");

while ($row = $users->fetch_assoc()) {
    echo "ID: " . $row['id'] . " - Name: " . $row['name'] . " - Email: " . $row['email'] . " - Role: " . $row['role'] . "<br>";
    echo "<a href='edit_user.php?id=" . $row['id'] . "'>Edit</a> | ";
    echo "<a href='delete_user.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure?\")'>Delete</a><br><br>";
}
?>
