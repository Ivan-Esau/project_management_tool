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

// Fetch tasks
$task_sql = "SELECT * FROM Tasks WHERE project_id='$project_id'";
$task_result = $conn->query($task_sql);

// Update task status
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Manage Tasks for Project ID: <?php echo $project_id; ?></h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($task = $task_result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $task['description']; ?></td>
                        <td><?php echo $task['status']; ?></td>
                        <td>
                            <form action="tasks.php?id=<?php echo $project_id; ?>" method="post">
                                <input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
                                <select name="status" class="form-select">
                                    <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                                    <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary mt-2">Change Status</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="project_details.php?id=<?php echo $project_id; ?>" class="btn btn-secondary">Back to Project Details</a>
    </div>
</body>
</html>
