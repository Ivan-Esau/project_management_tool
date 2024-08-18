<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task_id = $_POST['task_id'];
    $user_id = $_POST['user_id'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO comments (task_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $task_id, $user_id, $comment);

    if ($stmt->execute()) {
        echo "Comment added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="post" action="add_comment.php">
    Task ID: <input type="number" name="task_id" required><br>
    User ID: <input type="number" name="user_id" required><br>
    Comment: <textarea name="comment" required></textarea><br>
    <button type="submit">Add Comment</button>
</form>
