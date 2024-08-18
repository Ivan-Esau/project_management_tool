<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

    // Überprüfen, ob das aktuelle Passwort korrekt ist
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if (password_verify($current_password, $hashed_password)) {
        // Neues Passwort setzen
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_password, $user_id);

        if ($stmt->execute()) {
            echo "Password changed successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Current password is incorrect!";
    }

    $stmt->close();
}
?>

<form method="post" action="change_password.php">
    Current Password: <input type="password" name="current_password" required><br>
    New Password: <input type="password" name="new_password" required><br>
    <button type="submit">Change Password</button>
</form>
