<?php
include 'includes/db_connect.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current user's information
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM Users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

// Handle profile update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);

    // Only update the password if a new one was provided
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $update_sql = "UPDATE Users SET username='$username', email='$email', password='$password' WHERE id=$user_id";
    } else {
        $update_sql = "UPDATE Users SET username='$username', email='$email' WHERE id=$user_id";
    }

    if ($conn->query($update_sql) === TRUE) {
        $success_message = "Profile updated successfully.";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">User Profile</h2>

        <?php if (isset($success_message)) { ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php } elseif (isset($error_message)) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php } ?>

        <form action="profile.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (leave blank to keep current password):</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>

        <a href="dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</body>
</html>
