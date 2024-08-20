<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$project_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch the current project details
$project_sql = "SELECT * FROM Projects WHERE id = $project_id";
$project_result = $conn->query($project_sql);
$project = $project_result->fetch_assoc();

// Fetch the current user's team (if the user is an Employee)
$user_team_sql = "SELECT team_id FROM Users WHERE id = $user_id";
$user_team_result = $conn->query($user_team_sql);
$user_team = $user_team_result->fetch_assoc()['team_id'];

// Fetch assigned users
$assigned_users_sql = "SELECT user_id FROM Assignments WHERE project_id = $project_id";
$assigned_users_result = $conn->query($assigned_users_sql);
$assigned_users = [];
while ($row = $assigned_users_result->fetch_assoc()) {
    $assigned_users[] = $row['user_id'];
}

// Fetch project team (used to check if the project is part of the Employee's team)
$project_team_id = $project['team_id'];

// Allow access for Admins, Project Managers, and assigned Employees
if ($role == 'Admin' || $role == 'ProjectManager') {
    // Admins and Project Managers always have access
} elseif ($role == 'Employee' && $project_team_id == $user_team) {
    // Employees have access if the project is in their team
} else {
    echo "You do not have access to this project.";
    exit();
}

// Handle new project comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_project_comment'])) {
    $new_comment = $conn->real_escape_string($_POST['new_project_comment']);
    $insert_comment_sql = "INSERT INTO Comments (project_id, user_id, comment) VALUES ('$project_id', '$user_id', '$new_comment')";
    $conn->query($insert_comment_sql);
    header("Location: project_details.php?id=$project_id");
    exit();
}

// Handle new task-specific comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_task_comment']) && isset($_POST['task_id'])) {
    $task_id = (int)$_POST['task_id'];
    $new_comment = $conn->real_escape_string($_POST['new_task_comment']);
    $insert_comment_sql = "INSERT INTO TaskComments (task_id, user_id, comment) VALUES ('$task_id', '$user_id', '$new_comment')";
    $conn->query($insert_comment_sql);
    header("Location: project_details.php?id=$project_id#task-$task_id");
    exit();
}

// Fetch project comments
$comment_sql = "SELECT Comments.*, Users.username FROM Comments
                JOIN Users ON Comments.user_id = Users.id
                WHERE project_id='$project_id' ORDER BY Comments.created_at DESC";
$comment_result = $conn->query($comment_sql);

// Fetch tasks
$task_sql = "SELECT * FROM Tasks WHERE project_id='$project_id'";
$task_result = $conn->query($task_sql);

// Fetch task-specific comments
$task_comments = [];
$task_comment_sql = "SELECT TaskComments.*, Users.username
                     FROM TaskComments
                     JOIN Users ON TaskComments.user_id = Users.id
                     WHERE TaskComments.task_id = ?";
$task_comment_stmt = $conn->prepare($task_comment_sql);
$task_comment_stmt->bind_param("i", $task_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Project Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Project: <?php echo $project['name']; ?></h1>
        <p>Description: <?php echo $project['description']; ?></p>
        <p>Progress: <?php echo $project['progress']; ?>%</p>

        <h2 class="mt-5">Tasks</h2>
        <ul class="list-group mb-3">
            <?php while ($task = $task_result->fetch_assoc()) { ?>
                <li class="list-group-item">
                    <strong><?php echo $task['description']; ?> - <?php echo $task['status']; ?></strong>
                    <!-- Task comments button -->
                    <a href="#task-<?php echo $task['id']; ?>" data-bs-toggle="collapse" class="btn btn-link">Comments</a>

                    <!-- Task comments section -->
                    <div id="task-<?php echo $task['id']; ?>" class="collapse">
                        <div class="mt-3">
                            <?php
                            // Fetch comments for this task
                            $task_id = $task['id'];
                            $task_comment_stmt->execute();
                            $task_comment_result = $task_comment_stmt->get_result();
                            while ($task_comment = $task_comment_result->fetch_assoc()) { ?>
                                <div class="card mb-2">
                                    <div class="card-body">
                                        <p><strong><?php echo $task_comment['username']; ?>:</strong> <?php echo $task_comment['comment']; ?></p>
                                        <small class="text-muted"><?php echo $task_comment['created_at']; ?></small>
                                    </div>
                                </div>
                            <?php } ?>
                            <!-- New task-specific comment form -->
                            <form action="project_details.php?id=<?php echo $project_id; ?>" method="post" class="mt-3">
                                <input type="hidden" name="task_id" value="<?php echo $task_id; ?>">
                                <div class="mb-3">
                                    <textarea name="new_task_comment" class="form-control" placeholder="Add a comment..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Comment</button>
                            </form>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>

        <!-- Task creation form -->
        <h2 class="mt-5">Create a New Task</h2>
        <form action="create_task.php" method="post">
            <div class="mb-3">
                <label for="task_description" class="form-label">Task Description:</label>
                <input type="text" class="form-control" id="task_description" name="task_description" required>
            </div>
            <div class="mb-3">
                <label for="task_status" class="form-label">Status:</label>
                <select id="task_status" name="task_status" class="form-select" required>
                    <option value="To Do">To Do</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">
            <button type="submit" class="btn btn-primary">Create Task</button>
        </form>

        <h2 class="mt-5">Comments</h2>
        <ul class="list-group mb-3">
            <?php while ($comment = $comment_result->fetch_assoc()) { ?>
                <li class="list-group-item">
                    <strong><?php echo $comment['username']; ?>:</strong> <?php echo $comment['comment']; ?>
                    <br><small class="text-muted"><?php echo $comment['created_at']; ?></small>
                </li>
            <?php } ?>
        </ul>

        <form action="project_details.php?id=<?php echo $project_id; ?>" method="post">
            <div class="mb-3">
                <textarea name="new_project_comment" class="form-control" placeholder="Add a comment..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
