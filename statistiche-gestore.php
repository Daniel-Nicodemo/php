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

$stmt = $pdo->query("SELECT COUNT(*) FROM progressi_gioco WHERE indizio_chiave = 'primo'");
$primo = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM progressi_gioco WHERE indizio_chiave = 'secondo'");
$secondo = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM progressi_gioco WHERE indizio_chiave = 'dolce'");
$dolce = $stmt->fetchColumn();

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="pico-main/css/pico.min.css">
    <link rel="stylesheet" href="statistiche.css">
    <title>Statistiche Gestore</title>
</head>
<body>

    <h1>📊 Statistiche Gestore</h1>

    <!-- SEZIONE 1: Riepilogo numerico -->
    <h2>Riepilogo</h2>

    <div class="stat-box">
        <p><strong>Investigatori che hanno completato il gioco:</strong></p>
        <span><?= $tavoli_completato ?></span>
    </div>
    <br>
    <!-- SEZIONE 2: Riepilogo numerico -->
    <div class="stat-box">
        <p><strong>Investigatori che sono ancora in gioco:</strong></p>
        <span><?= $tavoli_totali ?></span>
    </div>

    <!-- SEZIONE 3: Progressi per portata -->
    <h2>Progressi Antipasto</h2>

    <div class="stat-box">
        <p>Investigatori che hanno sbloccato l'antipasto:</p>
        <span class="numero"><?= $antipasto ?> / <?= $tavoli_totali ?></span>
        
        <div class="bar-container">
            <div class="bar" style="width: <?= ($tavoli_totali > 0) ? ($antipasto / $tavoli_totali * 100) :  0?>%"></div>

        </div>
    </div>

    <h2>Progressi Primo</h2>

        <div class="stat-box">
            <p>Investigatori che hanno sbloccato il primo piatto:</p>
            <span class="numero"><?= $primo ?> / <?= $tavoli_totali ?></span>
        
        <div class="bar-container">
            <div class="bar" style="width: <?= ($tavoli_totali > 0) ? ($primo / $tavoli_totali * 100) :  0?>%"></div>

        </div>
    </div>


    <h2>Progressi Secondo</h2>

        <div class="stat-box">
            <p>Investigatori che hanno sbloccato il secondo piatto:</p>
            <span class="numero"><?= $secondo ?> / <?= $tavoli_totali ?></span>
        
        <div class="bar-container">
            <div class="bar" style="width: <?= ($tavoli_totali > 0) ? ($secondo / $tavoli_totali * 100) :  0?>%"></div>

        </div>
    </div>

    <h2>Progressi Dolce</h2>

        <div class="stat-box">
            <p>Investigatori che hanno sbloccato il dolce:</p>
            <span class="numero"><?= $dolce ?> / <?= $tavoli_totali ?></span>
        
        <div class="bar-container">
            <div class="bar" style="width: <?= ($tavoli_totali > 0) ? ($dolce / $tavoli_totali * 100) :  0?>%"></div>

        </div>
    </div>

</body>
</html>