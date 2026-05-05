
<?php
session_start();
require_once 'db.php'; // La tua connessione PDO semplice

// 1. Sicurezza: Se non c'è una sessione, riportalo all'inizio
if (!isset($_SESSION['tavolo_id'])) {
    header("Location: index.php");
    exit;
}

$tavolo = $_SESSION['tavolo_id'];
$chiave_indizio = 'antipasto'; // Cambia questo valore per ogni file (es. 'primo', 'dolce')

// 2. Controllo sblocco: L'utente ha davvero scansionato il QR, o ha scritto a mano l'url?
$stmt = $pdo->prepare("SELECT COUNT(*) FROM progressi_gioco WHERE tavolo_id = ? AND indizio_chiave = ?");
$stmt->execute([$tavolo, $chiave_indizio]);
$sbloccato = $stmt->fetchColumn();

if (!$sbloccato) {
    // Se non risulta nel DB, lo rimandiamo alla dashboard con un messaggio di errore
    header("Location: dashboard.php?errore=non_autorizzato");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indizio: <?php echo ucfirst($chiave_indizio); ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="clue-page">

    <div class="clue-container">
        <nav class="clue-nav">
            <a href="dashboard.php" class="back-link">⬅ Torna al Taccuino</a>
            <span class="clue-title">Fascicolo: <?php echo strtoupper($chiave_indizio); ?></span>
        </nav>

        <main class="clue-content">
            <h2>Indizio antipasto</h2>
            <hr>
            <p>
                Abbiamo analizzato i resti trovati nel bicchiere della vittima. 
                Le tracce di una sostanza amara indicano la presenza di <strong>cianuro</strong>.
            </p>
            <p>
                <em>Nota dell'investigatore:</em> La cuoca ha dichiarato di non aver usato mandorle nella cena. Qualcuno sta mentendo?
            </p>
            
            <div class="clue-image">
                <img src="images/referto_medico.jpg" alt="Referto" style="max-width: 100%;">
            </div>
        </main>
    </div>

</body>
</html>