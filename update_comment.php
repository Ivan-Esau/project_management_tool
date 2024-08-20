<?php
include 'includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment_id'])) {
    $comment_id = (int)$_POST['comment_id'];
    $comment = $conn->real_escape_string($_POST['comment']);

    $update_comment_sql = "UPDATE Comments SET comment='$comment' WHERE id='$comment_id'";

    if ($conn->query($update_comment_sql) === TRUE) {
        // Redirect back to project details
        $project_id = $conn->query("SELECT project_id FROM Comments WHERE id='$comment_id'")->fetch_assoc()['project_id'];
        header("Location: project_details.php?id=$project_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
