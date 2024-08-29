<?php
require 'db.php';

$projectId = $_GET['project_id'] ?? null;

if ($projectId) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM tasks WHERE project_id = :project_id ORDER BY created_at DESC');
        $stmt->bindParam(':project_id', $projectId, PDO::PARAM_INT);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($tasks);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Missing project ID.']);
}
?>
