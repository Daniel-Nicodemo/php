<?php
session_start();
include 'db.php';

// Identificazione tramite QR (modalità ristorante)
if (isset($_GET['tavolo'])) {
    $nome_tavolo = htmlspecialchars($_GET['tavolo']);
    $_SESSION['tavolo_id'] = $nome_tavolo;


} elseif (!isset($_SESSION['tavolo_id'])) {
    // Modalità online: assegna identità Guest
    $nomi = [
        "Ispettore_Leblanc", "Agente_Marlowe", "Detective_Cross",
        "Signora_Fletcher", "Commissario_Rex", "Investigatore_Bruno"
    ];
    //array_rand prende una o piu chiavi in modo random da un array, rand genera un numero intero casuale
    $_SESSION['tavolo_id'] = $nomi[array_rand($nomi)] . "_" . rand(10, 99);
}

$tavolo_attuale = $_SESSION['tavolo_id'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pranzo con Delitto</title>
    <link rel="stylesheet" href="statistiche.css">
</head>
<body>

    <div class="container">

        <h1>🕵️ Benvenuti al Pranzo con Delitto</h1>

        <div class="copione">

            <h2>Il Prologo</h2>

            <p>
                È una sera di novembre quando il conte Ardelio Mori viene trovato 
                senza vita nel suo studio. Il medico legale parla di cause naturali, 
                ma qualcosa non torna. Sul tavolo, un bicchiere di vino quasi intatto. 
                Nell'aria, un odore sottile di mandorle amare.
            </p>
            <p>
                Quattro persone erano presenti alla villa quella sera. 
                Quattro persone con un movente. Quattro persone che mentono.
            </p>
            <p>
                <strong>La verità è nascosta nel menù.</strong> 
                Solo chi saprà rispondere agli enigmi gastronomici potrà 
                raccogliere gli indizi e smascherare il colpevole.
            </p>

            <hr>

            <p class="identita">
                🔍 Identità assegnata: <strong><?= htmlspecialchars($tavolo_attuale) ?></strong>
            </p>

        </div>

        <a href="quiz.php?portata=antipasto" class="btn">
            🍽️ Affronta la prova dell'Antipasto
        </a>

    </div>

</body>
</html>
