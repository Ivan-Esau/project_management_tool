<?php
// db.php file should include your database connection code
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'];

    if ($id) {
        $query = $pdo->prepare('DELETE FROM projects WHERE id = :id');
        $query->bindParam(':id', $id, PDO::PARAM_INT);

        if ($query->execute()) {
            echo "Project deleted successfully.";
        } else {
            echo "Failed to delete project.";
        }
    } else {
        echo "No project ID provided.";
    }
}
?>
