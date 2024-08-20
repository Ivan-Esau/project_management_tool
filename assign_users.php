<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'ProjectManager'])) {
    header("Location: login.php");
    exit();
}

$project_id = $_GET['id'];

// Fetch unassigned users
$employee_sql = "SELECT * FROM Users WHERE role='Employee' AND id NOT IN (SELECT user_id FROM Assignments WHERE project_id='$project_id')";
$employee_result = $conn->query($employee_sql);

// Assign user to project
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $assign_sql = "INSERT INTO Assignments (project_id, user_id) VALUES ('$project_id', '$user_id')";
    $conn->query($assign_sql);
    header("Location: assign_users.php?id=$project_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Assign Users to Project</h1>

        <form action="assign_users.php?id=<?php echo $project_id; ?>" method="post">
            <div class="mb-3">
                <label for="user_id" class="form-label">Select User:</label>
                <select id="user_id" name="user_id" class="form-select" required>
                    <?php while ($employee = $employee_result->fetch_assoc()) { ?>
                        <option value="<?php echo $employee['id']; ?>"><?php echo $employee['username']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Assign User</button>
        </form>

        <a href="project_details.php?id=<?php echo $project_id; ?>" class="btn btn-secondary mt-3 w-100">Back to Project Details</a>
    </div>
</body>
</html>
