<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$project_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Projekt-Details abrufen
$sql = "SELECT * FROM Projects WHERE id='$project_id'";
$project_result = $conn->query($sql);
$project = $project_result->fetch_assoc();

// Aufgaben abrufen
$task_sql = "SELECT * FROM Tasks WHERE project_id='$project_id'";
$task_result = $conn->query($task_sql);

// Kommentare abrufen
$comment_sql = "SELECT * FROM Comments WHERE project_id='$project_id'";
$comment_result = $conn->query($comment_sql);

// Zugewiesene Teammitglieder abrufen
$team_sql = "SELECT Users.username FROM Users
             INNER JOIN Assignments ON Users.id = Assignments.user_id
             WHERE Assignments.project_id='$project_id'";
$team_result = $conn->query($team_sql);

// Neue Aufgabe hinzufügen (nur Projektmanager)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $role == 'ProjectManager') {
    if (isset($_POST['task_description'])) {
        $task_description = $_POST['task_description'];
        $insert_task_sql = "INSERT INTO Tasks (project_id, description) VALUES ('$project_id', '$task_description')";
        $conn->query($insert_task_sql);
        header("Location: project_details.php?id=$project_id");
        exit();
    }
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $insert_comment_sql = "INSERT INTO Comments (project_id, user_id, comment) VALUES ('$project_id', '$user_id', '$comment')";
        $conn->query($insert_comment_sql);
        header("Location: project_details.php?id=$project_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Projekt Details</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Projekt: <?php echo $project['name']; ?></h1>
    <p>Beschreibung: <?php echo $project['description']; ?></p>
    <p>Fortschritt: <?php echo $project['progress']; ?>%</p>

    <h2>Aufgaben</h2>
    <ul>
        <?php while ($task = $task_result->fetch_assoc()) {
            echo "<li>" . $task['description'] . " - " . $task['status'] . "</li>";
        } ?>
    </ul>

    <?php if ($role == 'ProjectManager') { ?>
        <form action="project_details.php?id=<?php echo $project_id; ?>" method="post">
            <label for="task_description">Neue Aufgabe:</label>
            <input type="text" id="task_description" name="task_description" required>
            <input type="submit" value="Aufgabe hinzufügen">
        </form>
    <?php } ?>

    <h2>Kommentare</h2>
    <ul>
        <?php while ($comment = $comment_result->fetch_assoc()) {
            echo "<li>" . $comment['comment'] . " (Hinzugefügt von User ID: " . $comment['user_id'] . " am " . $comment['timestamp'] . ")</li>";
        } ?>
    </ul>

    <form action="project_details.php?id=<?php echo $project_id; ?>" method="post">
        <label for="comment">Kommentar hinzufügen:</label>
        <textarea id="comment" name="comment" required></textarea>
        <input type="submit" value="Kommentar hinzufügen">
    </form>

    <h2>Zugewiesene Teammitglieder</h2>
    <ul>
        <?php while ($team_member = $team_result->fetch_assoc()) {
            echo "<li>" . $team_member['username'] . "</li>";
        } ?>
    </ul>

    <a href="dashboard.php">Zurück zum Dashboard</a>
</body>
</html>
