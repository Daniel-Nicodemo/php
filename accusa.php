<?php
include 'db.php';
session_start();

if (!isset($_SESSION['tavolo_id'])) { header("Location: index.php"); exit; }

$tavolo_id = $_SESSION['tavolo_id'];

// 1. Controllo se hanno già inviato un'accusa
$check = $pdo->prepare("SELECT id FROM accuse WHERE tavolo_id = ?");
$check->execute([$tavolo_id]);
$gia_votato = $check->fetch();

if ($gia_votato) {
    die("
    <link rel='stylesheet' href='pico-main/css/pico.min.css'>
    <link rel='stylesheet' href='statistiche.css'>
    <h1>Accusa già registrata.</h1><p>Il verdetto è nelle mani della giustizia. Non puoi più tornare indietro!</p><a href='dashboard.php'>Torna alla dashboard</a>
    ");
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>L'Accusa Finale</title>
    <link rel="stylesheet" href="pico-main/css/pico.min.css">
    <link rel="stylesheet" href="statistiche.css">
</head>
<body>
    <div class="container">
        <h1>🚨 VERDETTO FINALE 🚨</h1>
        <p>Attenzione: una volta inviata, l'accusa non potrà essere modificata.</p>

        <form action="salva_accusa.php" method="POST" class="form-accusa">
            
            <label>Chi è l'assassino?</label>
            <select name="colpevole" required>
                <option value="">-- Seleziona il sospettato --</option>
                <option value="Contessa Isabella">Contessa Isabella</option>
                <option value="Carla">Carla</option>
                <option value="Dott. ssa Bianchi">Dott. ssa Bianchi</option>
                <option value="Lucia">Cameriera Lucia</option>
                <option value="Maggiordomo">Il Maggiordomo</option>
                <option value="Anastasia">Anastasia</option>
            </select>

            <label>Qual è l'arma del delitto?</label>
            <select name="arma" required>
                <option value="">-- Seleziona l'arma --</option>
                <option value="Veleno">Veleno nel bicchiere</option>
                <option value="tagliacarte">Il tagliacarte nello studio</option>
                <option value="Cuscino">Soffocamento con cuscino</option>
                <option value="pistola">Pistola sotto la tavola</option>
            </select>

            <label>Descrivi il movente (Perché lo ha fatto?):</label>
            <textarea name="movente" rows="5" required placeholder="Inserisci qui i dettagli della tua indagine..."></textarea>

            <button type="submit" class="btn-submit">CONSEGNA IL VERDETTO</button>
        </form>
    </div>
</body>
</html>