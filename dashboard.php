<?php
include 'includes/db_connect.php';
session_start();

// Ensure session variables are set
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Initialize edit mode if it’s not set
if (!isset($_SESSION['edit_mode'])) {
    $_SESSION['edit_mode'] = false;
}

// Toggle edit mode
if ($role === 'Admin' && isset($_POST['toggle_edit_mode'])) {
    $_SESSION['edit_mode'] = !$_SESSION['edit_mode'];
}

// Fetch user details
$user_sql = "SELECT * FROM Users WHERE id='$user_id'";
$user_result = $conn->query($user_sql);

// Check if user data was fetched successfully
if ($user_result && $user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

// Fetch projects with team details
if ($role == 'Admin' || $role == 'ProjectManager') {
    $sql = "SELECT Projects.*, Teams.name AS team_name
            FROM Projects
            LEFT JOIN Teams ON Projects.team_id = Teams.id";
} else {
    $sql = "SELECT Projects.*, Teams.name AS team_name
            FROM Projects
            INNER JOIN Assignments ON Projects.id = Assignments.project_id
            LEFT JOIN Teams ON Projects.team_id = Teams.id
            WHERE Assignments.user_id = '$user_id'";
}

$result = $conn->query($sql);

// Ensure the query was successful
if (!$result) {
    echo "Failed to fetch projects: " . $conn->error;
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Project Management Tool</a>
            <div class="dropdown ms-auto">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://via.placeholder.com/30" alt="User Icon" class="rounded-circle me-2">
                    <?php echo $user['username']; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li> <!-- Updated link -->
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4">Dashboard</h1>
        <?php if ($role == 'Admin') { ?>
            <form action="dashboard.php" method="post" class="mb-4">
                <button type="submit" name="toggle_edit_mode" class="btn btn-<?php echo $_SESSION['edit_mode'] ? 'danger' : 'primary'; ?>">
                    <?php echo $_SESSION['edit_mode'] ? 'Exit Edit Mode' : 'Enter Edit Mode'; ?>
                </button>
            </form>
        <?php } ?>
        <?php if ($_SESSION['edit_mode']) { ?>
            <a href="add_project.php" class="btn btn-primary mb-4">Add New Project</a>
        <?php } ?>

        <div class="row">
            <?php while ($project = $result->fetch_assoc()) { ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><?php echo $project['name']; ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo $project['description']; ?></p>
                            <div class="progress mb-2">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo $project['progress']; ?>%;" aria-valuenow="<?php echo $project['progress']; ?>" aria-valuemin="0" aria-valuemax="100">
                                    <?php echo $project['progress']; ?>%
                                </div>
                            </div>
                            <?php if (in_array($role, ['Admin', 'ProjectManager'])) { ?>
                                <p class="mb-0">Team: <?php echo $project['team_name'] ? $project['team_name'] : 'Unassigned'; ?></p>
                            <?php } ?>
                        </div>
                        <div class="card-footer">
                            <a href="project_details.php?id=<?php echo $project['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
                            <?php if ($_SESSION['edit_mode'] && $role == 'Admin') { ?>
                                <a href="edit_project.php?id=<?php echo $project['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
