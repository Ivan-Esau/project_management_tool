<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_POST['project_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    if ($projectId && $name) {
        $query = $pdo->prepare('INSERT INTO tasks (project_id, name, description) VALUES (:project_id, :name, :description)');
        $query->bindParam(':project_id', $projectId, PDO::PARAM_INT);
        $query->bindParam(':name', $name);
        $query->bindParam(':description', $description);

        if ($query->execute()) {
            echo "Task added successfully.";
        } else {
            echo "Failed to add task.";
        }
    } else {
        echo "Invalid data.";
    }
}
?>
