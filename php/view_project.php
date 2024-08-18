<?php
require 'config.php';

$project_id = $_GET['id'];

$stmt = $conn->prepare("SELECT title, description, start_date, end_date, status FROM projects WHERE id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();
$stmt->bind_result($title, $description, $start_date, $end_date, $status);
$stmt->fetch();

echo "<h1>$title</h1>";
echo "<p>Description: $description</p>";
echo "<p>Start Date: $start_date</p>";
echo "<p>End Date: $end_date</p>";
echo "<p>Status: $status</p>";

$stmt->close();
?>
