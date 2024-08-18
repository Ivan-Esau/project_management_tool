<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO projects (title, description, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $description, $start_date, $end_date, $status);

    if ($stmt->execute()) {
        echo "Project created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="post" action="create_project.php">
    Title: <input type="text" name="title" required><br>
    Description: <textarea name="description" required></textarea><br>
    Start Date: <input type="date" name="start_date" required><br>
    End Date: <input type="date" name="end_date" required><br>
    Status:
    <select name="status" required>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
    </select><br>
    <button type="submit">Create Project</button>
</form>
