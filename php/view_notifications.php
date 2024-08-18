<?php
require 'config.php';

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT message, created_at, is_read FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($message, $created_at, $is_read);

echo "<h1>Notifications</h1>";
while ($stmt->fetch()) {
    echo "<p>" . ($is_read ? '' : '<strong>') . "$message - $created_at" . ($is_read ? '' : '</strong>') . "</p>";
}

$stmt->close();
?>
