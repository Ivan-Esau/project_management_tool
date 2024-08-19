<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$project_id = $_GET['id'];
$role = $_SESSION['role'];

if ($role != 'ProjectManager') {
    header("Location: dashboard.php");
    exit();
}

// Aufgaben abrufen
$task_sql = "SELECT * FROM Tasks WHERE project_id='$project_id'";
$task_result = $conn->query($task_sql);

// Aufgabe aktualisieren (Status ändern)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    $update_task_sql = "UPDATE Tasks SET status='$status' WHERE id='$task_id'";
    $conn->query($update_task_sql);
    header("Location: tasks.php?id=$project_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Aufgaben verwalten</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Aufgaben verwalten für Projekt ID: <?php echo $project_id; ?></h1>

    <table border="1">
        <tr>
            <th>Aufgabe</th>
            <th>Status</th>
            <th>Aktionen</th>
        </tr>
        <?php while ($task = $task_result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $task['description']; ?></td>
                <td><?php echo $task['status']; ?></td>
                <td>
                    <form action="tasks.php?id=<?php echo $project_id; ?>" method="post">
                        <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                        <select name="status">
                            <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        </select>
                        <input type="submit" value="Status ändern">
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <a href="project_details.php?id=<?php echo $project_id; ?>">Zurück zu den Projekt-Details</a>
</body>
</html>
