<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Überprüfen, ob die E-Mail oder der Name bereits existiert
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR name = ? LIMIT 1");
    $stmt->bind_param("ss", $email, $name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Wenn Benutzername oder E-Mail bereits existieren
        echo "<script>alert('Registration failed: Email or Username already exists.'); window.location.href='../index.html';</script>";
    } else {
        // Benutzer erstellen, wenn die Überprüfung bestanden ist
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!'); window.location.href='../index.html';</script>";
        } else {
            echo "<script>alert('Error: " . $stmt->error . "'); window.location.href='../index.html';</script>";
        }
    }

    $stmt->close();
}
