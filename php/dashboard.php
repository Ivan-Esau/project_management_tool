<?php
require 'config.php';

// Anzahl der laufenden und abgeschlossenen Projekte
$project_stats = $conn->query("
    SELECT
        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed
    FROM projects
")->fetch_assoc();

// Anzahl der Aufgaben pro Status
$task_status_stats = $conn->query("
    SELECT
        SUM(CASE WHEN status = 'Open' THEN 1 ELSE 0 END) AS open,
        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress,
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed
    FROM tasks
")->fetch_assoc();

// Anzahl der Aufgaben pro Priorität
$task_priority_stats = $conn->query("
    SELECT
        SUM(CASE WHEN priority = 'Low' THEN 1 ELSE 0 END) AS low,
        SUM(CASE WHEN priority = 'Medium' THEN 1 ELSE 0 END) AS medium,
        SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) AS high
    FROM tasks
")->fetch_assoc();
?>

<h1>Dashboard</h1>

<h2>Projekt-Statistiken</h2>
<p>In Bearbeitung: <?php echo $project_stats['in_progress']; ?></p>
<p>Abgeschlossen: <?php echo $project_stats['completed']; ?></p>

<h2>Aufgaben-Statistiken nach Status</h2>
<p>Offen: <?php echo $task_status_stats['open']; ?></p>
<p>In Bearbeitung: <?php echo $task_status_stats['in_progress']; ?></p>
<p>Abgeschlossen: <?php echo $task_status_stats['completed']; ?></p>

<h2>Aufgaben-Statistiken nach Priorität</h2>
<p>Niedrig: <?php echo $task_priority_stats['low']; ?></p>
<p>Mittel: <?php echo $task_priority_stats['medium']; ?></p>
<p>Hoch: <?php echo $task_priority_stats['high']; ?></p>
