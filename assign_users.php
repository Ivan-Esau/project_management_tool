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

// Nicht zugewiesene Benutzer abrufen
$employee_sql = "SELECT * FROM Users WHERE role='Employee' AND id NOT IN (SELECT user_id FROM Assignments WHERE project_id='$project_id')";
$employee_result = $conn->query($employee_sql);

// Benutzer zuweisen
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $assign_sql = "INSERT INTO Assignments (project_id, user_id) VALUES ('$project_id', '$user_id')";
    $conn->query($assign_sql);
    header("Location: assign_users.php?id=$project_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Benutzer zuweisen</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Benutzer zuweisen zu Projekt ID: <?php echo $project_id; ?></h1>

    <form action="assign_users.php?id=<?php echo $project_id; ?>" method="post">
        <label for="user_id">Benutzer auswählen:</label>
        <select id="user_id" name="user_id" required>
            <?php while ($employee = $employee_result->fetch_assoc()) {
                echo "<option value='" . $employee['id'] . "'>" . $employee['username'] . "</option>";
            } ?>
        </select>
        <input type="submit" value="Benutzer zuweisen">
    </form>

    <a href="project_details.php?id=<?php echo $project_id; ?>">Zurück zu den Projekt-Details</a>
</body>
</html>
