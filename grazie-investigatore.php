<?php

session_start();
include "db.php";

if(!isset($_SESSION['tavolo_id'])){

    header("Location: index.php");
    exit;
}

$colpevole_reale = "Elena";
$arma_reale = "Veleno";

$tavolo_id = $_SESSION['tavolo_id'];

//recuperiamo accusa investigatore

$stmt = $pdo->prepare("SELECT * FROM accuse WHERE tavolo_id = ?");
$stmt->execute([$tavolo_id]);

$accusa = $stmt->fetch();

//se non esiste una accusa torniamo al form

if(!$accusa){

    header("Location: accusa.php");
    exit;
}

//confronto con la soluzione reale (TRUE o FALSE)

$ha_indovinato = ($accusa['colpevole'] === $colpevole_reale && $accusa['arma'] === $arma_reale);

?>
 
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Il tuo Verdetto</title>
    <link rel="stylesheet" href="pico-main/css/pico.min.css">
</head>
<body>
    <div class="container">
 
        <h1>🕵️ Il tuo Verdetto</h1>
 
        <!-- SEZIONE 1: Riepilogo accusa inviata -->
        <div class="clue-card">
            <h2>La tua accusa</h2>
            <p><strong>Colpevole:</strong> <?= htmlspecialchars($accusa['colpevole']) ?></p>
            <p><strong>Arma:</strong> <?= htmlspecialchars($accusa['arma']) ?></p>
            <p><strong>Movente:</strong> <?= htmlspecialchars($accusa['movente']) ?></p>
        </div>
 
        <!-- SEZIONE 2: Confronto con la soluzione reale -->
        <div class="clue-card">
            <h2>La soluzione del caso</h2>
            <p>
                <strong>Colpevole:</strong> <?= htmlspecialchars($colpevole_reale) ?>
                <?= ($accusa['colpevole'] === $colpevole_reale) ? '✅' : '❌' ?>
            </p>
            <p>
                <strong>Colpevole:</strong> <?= htmlspecialchars($arma_reale) ?>
                <?= ($accusa['arma'] === $arma_reale) ? '✅' : '❌' ?>
            </p>
        </div>
 
        <!-- SEZIONE 3: Esito finale -->
        
        <!--se il colpevole ha indovinato visualizza questo messaggio -->

        <?php if($ha_indovinato): ?>

            <div class="alert alert-success">
                <h2>🎉 Complimenti, investigatore!</h2>
                <p>Hai risolto il caso. La giustizia ha trionfato!</p>
            </div>
            <a href="dashboard.php" class="btn">Rivedi gli indizi raccolti</a>
 
        <!--se il colpevole non ha indovinato visualizza questo messaggio -->

        <?php else: ?>    

            <div class="alert alert-error">
                <h2>❌ Indagine fallita</h2>
                <p>Il colpevole è ancora a piede libero. Vuoi ricominciare l'indagine da capo?</p>
            </div>
            <a href="reset.php" class="btn">🔄 Ricomincia l'indagine</a>
 
       <?php endif; ?>
 
    </div>
</body>
</html>
