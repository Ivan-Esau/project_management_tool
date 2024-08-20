<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    if ($id) {
        $query = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        if ($query->execute()) {
            echo "Task deleted successfully.";
        } else {
            echo "Failed to delete task.";
        }
    } else {
        echo "No task ID provided.";
    }
}
?>
