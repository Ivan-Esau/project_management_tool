<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'];

    $stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description, due_date, priority, status, assigned_to) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssi", $project_id, $title, $description, $due_date, $priority, $status, $assigned_to);

    if ($stmt->execute()) {
        echo "Task created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="post" action="create_task.php">
    Project ID: <input type="number" name="project_id" required><br>
    Title: <input type="text" name="title" required><br>
    Description: <textarea name="description" required></textarea><br>
    Due Date: <input type="date" name="due_date" required><br>
    Priority:
    <select name="priority" required>
        <option value="Low">Low</option>
        <option value="Medium">Medium</option>
        <option value="High">High</option>
    </select><br>
    Status:
    <select name="status" required>
        <option value="Open">Open</option>
        <option value="In Progress">In Progress</option>
        <option value="Completed">Completed</option>
    </select><br>
    Assigned To (User ID): <input type="number" name="assigned_to" required><br>
    <button type="submit">Create Task</button>
</form>
