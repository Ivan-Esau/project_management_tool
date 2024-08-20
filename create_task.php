<?php
include 'includes/db_connect.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = (int)$_POST['project_id'];
    $task_description = $conn->real_escape_string($_POST['task_description']);
    $task_status = $conn->real_escape_string($_POST['task_status']);

    // Insert the new task into the database
    $sql = "INSERT INTO Tasks (project_id, description, status) VALUES ('$project_id', '$task_description', '$task_status')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the project details page
        header("Location: project_details.php?id=$project_id");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    // Redirect to the project details page if the form was not submitted
    header("Location: project_details.php");
    exit();
}
?>
