<?php
include 'db.php';

// --- QUERY STATISTICHE ---

// 1) quanti tavoli hanno completato il gioco

$stmt = $pdo->query("SELECT COUNT(DISTINCT tavolo_id) FROM accuse");
$tavoli_completato = $stmt->fetchColumn();

// 2) quanti tavoli hanno indizio ma non hanno accusato
$stmt = $pdo->query("SELECT COUNT(DISTINCT tavolo_id) FROM progressi_gioco");
$tavoli_totali = $stmt->fetchColumn();

// 3) quanti investigatori hanno sbloccato l'antipasto
$stmt = $pdo->query("SELECT COUNT(*) FROM progressi_gioco WHERE indizio_chiave = 'antipasto'");
$antipasto = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiche Gestore</title>
    <style>
        
        .bar-container {
            background: #333;
            border-radius: 4px;
            height: 20px;
            width: 100%;
            margin-top: 4px;
        }
        .bar {
            background: #ff4d4d;
            height: 20px;
            border-radius: 4px;
        }
        
    </style>
</head>
<body>

    <h1>📊 Statistiche Gestore</h1>

    <!-- SEZIONE 1: Riepilogo numerico -->
    <h2>Riepilogo</h2>

    <div class="stat-box">
        <p>Investigatori che hanno completato il gioco:</p>
        <span><?= $tavoli_completato ?></span>
    </div>

    <!-- SEZIONE 2: Riepilogo numerico -->
    <div class="stat-box">
        <p>Investigatori che sono ancora in gioco:</p>
        <span><?= $tavoli_totali ?></span>
    </div>

    <!-- SEZIONE 3: Progressi per portata -->
    <h2>Progressi Antipasto</h2>

    <div class="stat-box">
        <p>Investigatori che hanno sbloccato l'antipasto</p>
        <span class="numero"><?= $antipasto ?> / <?= $tavoli_totali ?></span>
        
        <div class="bar-container">
            <div class="bar" style="width: <?= ($tavoli_totali > 0) ? ($antipasto / $tavoli_totali * 100) :  0?>%"></div>

        </div>
    </div>


</body>
</html>