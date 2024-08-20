<?php
// Database connection
$host = 'localhost';
$dbname = 'project_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form data is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;

        if ($name && $description) {
            // Insert the new project into the database
            $stmt = $pdo->prepare('INSERT INTO projects (name, description) VALUES (:name, :description)');
            $stmt->execute(['name' => $name, 'description' => $description]);

            echo 'Project added successfully!';
        } else {
            echo 'Error: Missing project name or description.';
        }
    } else {
        echo 'Invalid request method.';
    }
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
