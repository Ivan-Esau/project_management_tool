<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($role == 'Admin' || $role == 'ProjectManager') {
    $sql = "SELECT * FROM Projects";
} else {
    $sql = "SELECT * FROM Projects
            INNER JOIN Assignments ON Projects.id = Assignments.project_id
            WHERE Assignments.user_id = '$user_id'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Dashboard</h1>
    <table border="1">
        <tr>
            <th>Project Name</th>
            <th>Description</th>
            <th>Progress</th>
        </tr>
        <?php
        while ($project = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td><a href='project_details.php?id=" . $project['id'] . "'>" . $project['name'] . "</a></td>";
            echo "<td>" . $project['description'] . "</td>";
            echo "<td>" . $project['progress'] . "%</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>
