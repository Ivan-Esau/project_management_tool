<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Überprüfen, ob die POST-Daten gesetzt sind
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Überprüfen, ob der Benutzername existiert
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Passwort verifizieren und Session-Start
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            // Benutzer wird nach erfolgreichem Login zum Dashboard weitergeleitet
            header('Location: dashboard.php');
            exit();
        } else {
            // Bei falschen Login-Daten zur Login-Seite zurückkehren und Fehler anzeigen
            header('Location: ../index.html?error=1');
            exit();
        }
    } else {
        // Falls Eingaben fehlen, zurück zur Login-Seite und Fehler anzeigen
        header('Location: ../index.html?error=1');
        exit();
    }
}
?>
