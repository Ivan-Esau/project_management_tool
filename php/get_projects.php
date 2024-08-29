<?php
require 'db.php';

try {
    $stmt = $pdo->prepare('SELECT * FROM projects ORDER BY created_at DESC');
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($projects);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
