<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php"); // Wenn der Benutzer eingeloggt ist, zum Dashboard weiterleiten
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Project Management Tool</title>
    <!-- Optional: Include Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome</h2>
    <p>Please log in to continue</p>
    <a href="login.php" class="btn btn-primary btn-block">Login</a>
    <p class="text-center mt-3">Don't have an account? <a href="register.php">Register here</a></p>
</div>

</body>
</html>
