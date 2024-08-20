<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // User is not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

// Assuming you've connected to the database already
include 'includes/db_connect.php';

// Fetch the logged-in user's information
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM Users WHERE id = $user_id";
$user_result = $conn->query($user_sql);

if ($user_result->num_rows == 0) {
    // No user found with the given ID, redirect to login page with an error
    session_destroy();
    header("Location: login.php?error=User+not+found!");
    exit();
}

// Continue with the rest of your application logic...
