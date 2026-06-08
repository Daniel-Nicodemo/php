<?php
include 'db.php'; // La tua connessione PDO semplice
session_start();

// 1. Sicurezza: Se non c'è una sessione, riportalo all'inizio
if (!isset($_SESSION['tavolo_id'])) {
    header("Location: index.php");
    exit;
}

$tavolo = $_SESSION['tavolo_id'];
$chiave_indizio = 'primo'; // Cambia questo valore per ogni file (es. 'primo', 'dolce')

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
    <link rel="stylesheet" href="pico-main/css/pico.min.css">
    <link rel="stylesheet" href="statistiche.css">
</head>
<body class="clue-page">

    <div class="clue-container">
        <nav class="clue-nav">
            <a href="dashboard.php" class="back-link">⬅ Torna al Taccuino</a>
        </nav>

        <main class="clue-content">
            <h2>Indizio Primo</h2>
            <hr>
            <ul>
                <li>La Contessa erediterà una grande fortuna.</li>
                <li>Carla ha perso soldi al gioco e ha bisogno di denaro.</li>
                <li>La dottoressa aveva in cura il Conte ma falsificava ricette.</li>
                <li>La Cameriera era stata sorpresa a curiosare nello studio del padrone.</li>
                <li>Anastasia passava molto tempo al fianco del conte ed era la sua consigliera.</li>
                <li>Alfred aveva un grosso debito in sospeso col Conte.</li>
            </ul>
 
        </main>
    </div>

</body>
</html>