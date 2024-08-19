<?php
session_start();

// If the user is already logged in, redirect them to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
} else {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}
?>
