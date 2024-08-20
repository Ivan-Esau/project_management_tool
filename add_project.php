<?php
include 'includes/db_connect.php';
session_start();

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Admin', 'ProjectManager'])) {
    header("Location: login.php");
    exit();
}

// Fetch teams for the dropdown
$team_sql = "SELECT * FROM Teams";
$team_result = $conn->query($team_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_name = $conn->real_escape_string($_POST['project_name']);
    $description = $conn->real_escape_string($_POST['description']);
    $team_id = (int)$_POST['team_id'];
    $progress = 0; // Start with 0% progress

    $sql = "INSERT INTO Projects (name, description, progress, team_id) VALUES ('$project_name', '$description', $progress, $team_id)";

    if ($conn->query($sql) === TRUE) {
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
    <title>Add Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add Project</h2>
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
        <?php } ?>
        <form action="add_project.php" method="post">
            <div class="mb-3">
                <label for="project_name" class="form-label">Project Name:</label>
                <input type="text" class="form-control" id="project_name" name="project_name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="team_id" class="form-label">Assign to Team:</label>
                <select id="team_id" name="team_id" class="form-select" required>
                    <?php while ($team = $team_result->fetch_assoc()) { ?>
                        <option value="<?php echo $team['id']; ?>"><?php echo $team['name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Project</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary mt-3 w-100">Cancel</a>
    </div>
</body>
</html>
