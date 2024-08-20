<?php
include 'includes/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    $description = $conn->real_escape_string($_POST['description']);
    $status = $conn->real_escape_string($_POST['status']);

    $update_task_sql = "UPDATE Tasks SET description='$description', status='$status' WHERE id='$task_id'";

    if ($conn->query($update_task_sql) === TRUE) {
        // Redirect back to project details
        $project_id = $conn->query("SELECT project_id FROM Tasks WHERE id='$task_id'")->fetch_assoc()['project_id'];
        header("Location: project_details.php?id=$project_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
