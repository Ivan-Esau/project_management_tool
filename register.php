<?php
include 'includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash the password
    $email = $_POST['email'];
    $role = $_POST['role'];
    $team_id = $_POST['team_id'];

    $sql = "INSERT INTO Users (username, password, email, role, team_id)
            VALUES ('$username', '$password', '$email', '$role', '$team_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        header("Location: login.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="Admin">Admin</option>
            <option value="ProjectManager">Project Manager</option>
            <option value="Employee">Employee</option>
        </select><br>

        <label for="team_id">Team:</label>
        <select id="team_id" name="team_id" required>
            <option value="1">Development</option>
            <option value="2">Design</option>
            <option value="3">Marketing</option>
        </select><br>

        <input type="submit" value="Register">
    </form>
</body>
</html>
