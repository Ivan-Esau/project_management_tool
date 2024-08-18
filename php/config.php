<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "project_management_tool";

// Verbindung zur Datenbank herstellen
$conn = new mysqli($servername, $username, $password, $database);

// Fehlerbehandlung bei Verbindungsproblemen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Speicherlimit prüfen und anpassen (Optional)
ini_set('memory_limit', '512M'); // Setzt ein Limit von 512 MB (falls nötig, anpassbar)

// Funktion zur Rollenprüfung, um Endlosschleifen zu vermeiden
function check_role($required_role) {
    session_start();
    if ($_SESSION['user_role'] !== $required_role) {
        echo "Access denied. You do not have the required permissions.";
        exit();
    }
}
?>
