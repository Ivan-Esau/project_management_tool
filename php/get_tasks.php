<?php
require 'db.php';

$projectId = $_GET['project_id'];

if ($projectId) {
    $query = $pdo->prepare('SELECT * FROM tasks WHERE project_id = :project_id');
    $query->bindParam(':project_id', $projectId, PDO::PARAM_INT);
    $query->execute();

    echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
} else {
    echo json_encode([]);
}
?>
