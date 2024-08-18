<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfen, ob die POST-Daten gesetzt sind
    if (isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = 'user'; // Standardrolle

        // Überprüfen, ob die E-Mail oder der Benutzername bereits existieren
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $username]);
        $existingUser = $stmt->fetchColumn();

        if ($existingUser > 0) {
            // Wenn Benutzername oder E-Mail existieren, Umleitung mit einem spezifischen Fehlercode
            header('Location: ../register.html?error=exists');
            exit(); // Stoppe die Ausführung des Skripts hier
        } else {
            // Einfügen des neuen Benutzers in die Datenbank
            $stmt = $pdo->prepare('INSERT INTO users (email, username, password, role) VALUES (?, ?, ?, ?)');
            if ($stmt->execute([$email, $username, $password, $role])) {
                header('Location: ../index.html?registered=1');
                exit(); // Stoppe die Ausführung des Skripts hier
            } else {
                header('Location: ../register.html?error=database');
                exit(); // Stoppe die Ausführung des Skripts hier
            }
        }
    } else {
        header('Location: ../register.html?error=missing');
        exit(); // Stoppe die Ausführung des Skripts hier
    }
}
?>
