<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = $_GET['project_id'] ?? null;
    $taskName = $_POST['taskName'] ?? '';
    $taskDescription = $_POST['taskDescription'] ?? '';

    if ($projectId && !empty($taskName) && !empty($taskDescription)) {
        try {
            $stmt = $pdo->prepare('INSERT INTO tasks (project_id, name, description) VALUES (:project_id, :name, :description)');
            $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
            $stmt->bindParam(':name', $taskName);
            $stmt->bindParam(':description', $taskDescription);
            $stmt->execute();

            echo json_encode(['message' => 'Task added successfully.']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['error' => 'Missing project ID, task name, or task description.']);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
