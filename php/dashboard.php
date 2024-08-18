<?php
session_start();
include 'db.php';

// Überprüfen, ob der Benutzer angemeldet ist
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

// Projekte aus der Datenbank abrufen
$stmt = $pdo->prepare('SELECT * FROM projects WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// HTML einbinden und Projekte dynamisch einfügen
ob_start();
include 'dashboard.html';
$htmlContent = ob_get_clean();

$projectListHtml = '';
if (count($projects) > 0) {
    foreach ($projects as $project) {
        $projectListHtml .= '<li class="list-group-item">';
        $projectListHtml .= '<strong>' . htmlspecialchars($project['title']) . '</strong><br>';
        $projectListHtml .= htmlspecialchars($project['description']) . '<br>';
        $projectListHtml .= '<em>Startdatum: ' . htmlspecialchars($project['start_date']) . '</em><br>';
        $projectListHtml .= '<em>Enddatum: ' . htmlspecialchars($project['end_date']) . '</em>';
        $projectListHtml .= '</li>';
    }
} else {
    $projectListHtml = '<p>Keine Projekte vorhanden.</p>';
}

// Platzhalter im HTML durch die Liste der Projekte ersetzen
$htmlContent = str_replace('<!-- Projekte werden hier eingefügt -->', $projectListHtml, $htmlContent);

// Die endgültige HTML-Seite anzeigen
echo $htmlContent;
?>
