<?php
// Database connection
$host = 'localhost';
$dbname = 'project_management';
$username = 'root'; // Default username for Laragon
$password = ''; // Default password is empty for Laragon

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch projects from the database
    $stmt = $pdo->query('SELECT name, description FROM projects');
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return projects as JSON
    echo json_encode($projects);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
