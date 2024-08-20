<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'ProjectManager'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] != 'Admin' || !$_SESSION['edit_mode']) {
    header("Location: dashboard.php");
    exit();
}

$project_id = (int)$_GET['id'];

// Fetch the current project details
$project_sql = "SELECT * FROM Projects WHERE id = $project_id";
$project_result = $conn->query($project_sql);
$project = $project_result->fetch_assoc();

// Fetch teams for the dropdown
$team_sql = "SELECT * FROM Teams";
$team_result = $conn->query($team_sql);

// Fetch all users
$user_sql = "SELECT * FROM Users";
$user_result = $conn->query($user_sql);

// Fetch assigned users
$assigned_users_sql = "SELECT user_id FROM Assignments WHERE project_id = $project_id";
$assigned_users_result = $conn->query($assigned_users_sql);
$assigned_users = [];
while ($row = $assigned_users_result->fetch_assoc()) {
    $assigned_users[] = $row['user_id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = $conn->real_escape_string($_POST['project_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $team_id = (int)$_POST['team_id'];
    $progress = (int)$_POST['progress'];
    $assigned_users = isset($_POST['assigned_users']) ? $_POST['assigned_users'] : [];

    // Always include Admin and ProjectManager in the assignments
    $role_sql = "SELECT id FROM Users WHERE role IN ('Admin', 'ProjectManager')";
    $role_result = $conn->query($role_sql);
    while ($row = $role_result->fetch_assoc()) {
        $assigned_users[] = $row['id'];
    }

    // Ensure no duplicate assignments
    $assigned_users = array_unique($assigned_users);

    // Update project details
    $update_sql = "UPDATE Projects
                   SET name = '$project_name', description = '$description', team_id = $team_id, progress = $progress
                   WHERE id = $project_id";

    if ($conn->query($update_sql) === TRUE) {
        // Remove all existing assignments
        $conn->query("DELETE FROM Assignments WHERE project_id = $project_id");

        // Assign selected users to the project
        foreach ($assigned_users as $user_id) {
            $user_id = (int)$user_id;
            $conn->query("INSERT INTO Assignments (project_id, user_id) VALUES ($project_id, $user_id)");
        }

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Project</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form action="edit_project.php?id=<?php echo $project_id; ?>" method="post">
            <div class="mb-3">
                <label for="project_name" class="form-label">Project Name:</label>
                <input type="text" class="form-control" id="project_name" name="project_name" value="<?php echo $project['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" required><?php echo $project['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="team_id" class="form-label">Assign to Team:</label>
                <select id="team_id" name="team_id" class="form-select" required>
                    <?php while ($team = $team_result->fetch_assoc()) { ?>
                        <option value="<?php echo $team['id']; ?>" <?php if ($team['id'] == $project['team_id']) echo 'selected'; ?>>
                            <?php echo $team['name']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="progress" class="form-label">Progress (%):</label>
                <input type="number" class="form-control" id="progress" name="progress" value="<?php echo $project['progress']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="assigned_users" class="form-label">Assign Users:</label>
                <div class="form-check">
                    <?php while ($user = $user_result->fetch_assoc()) { ?>
                        <div>
                            <input class="form-check-input" type="checkbox" id="user_<?php echo $user['id']; ?>" name="assigned_users[]" value="<?php echo $user['id']; ?>" <?php if (in_array($user['id'], $assigned_users)) echo 'checked'; ?>>
                            <label class="form-check-label" for="user_<?php echo $user['id']; ?>">
                                <?php echo $user['username']; ?> (<?php echo $user['role']; ?>)
                            </label>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Project</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Cancel</a>
    </div>
</body>
</html>
