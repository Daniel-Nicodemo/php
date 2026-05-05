<?php
session_start();
include 'db.php';

if (!isset($_SESSION['tavolo_id'])) {
    header("Location: index.php");
    exit;
}

$tavolo = $_SESSION['tavolo_id'];

// Recupero indizi sbloccati
$stmt = $pdo->prepare("SELECT indizio_chiave FROM progressi_gioco WHERE tavolo_id = ?");
$stmt->execute([$tavolo]);
$sbloccati = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Etichette leggibili per le portate
$etichette = [
    'antipasto' => 'Antipasto',
    'primo'     => 'Primo Piatto',
    'secondo'   => 'Secondo Piatto',
    'dolce'     => 'Dolce',
];
?>




<!-- view -->

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Investigativa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>🕵️ Taccuino: <?= htmlspecialchars($tavolo) ?></h1>
    </header>

    <main>

        <!-- SEZIONE 1: Indizi raccolti -->

        <section class="clue-list">
            <h2>Indizi Raccolti</h2>

            <?php if (empty($sbloccati)): ?>
                <div class="alert">
                    <p>Non hai ancora trovato indizi. Affronta la prima prova!</p>
                </div>

            <?php else: ?>

                <div class="grid">
                    <?php foreach ($sbloccati as $valore): ?>
                        <div class="clue-card">
                            <h3>🔍 Indizio: <?php echo ucfirst($valore); ?></h3>
                            <p>Nuove informazioni sono state aggiunte al caso.</p>
                            <a href="indizio_<?php echo $valore; ?>.php" class="btn-link">Apri Fascicolo</a>
                        </div>
                    <?php endforeach; ?>

                </div>

            <?php endif; ?>


        </section>

        <hr>

        <!-- SEZIONE 2: Prove da affrontare -->
        <section class="prove">
            <h2>🍽️ Prove Gastronomiche</h2>
            <div class="grid">

                

            </div>
        </section>

        <hr>

        <!-- Accusa finale: solo dopo il dolce -->
        <?php if (in_array('dolce', $sbloccati)): ?>
            <section class="final-action">
                <a href="accusa.php" class="btn-accusa">🚨 FORMULA L'ACCUSA FINALE 🚨</a>
            </section>
        <?php endif; ?>

    </main>

    <!-- Classifica: appare quando almeno un tavolo ha votato -->
    <div id="box-classifica"></div>

    <script>
        function controllaClassifica() {
            fetch('check_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.pubblicata === true) {
                        document.getElementById('box-classifica').innerHTML = `
                            <div style="background:gold; padding:20px; text-align:center;">
                                <a href="classifica-finale.php">🏆 Scopri i Vincitori!</a>
                            </div>`;
                    }
                })
                .catch(err => console.log('Errore:', err));
        }

        setInterval(controllaClassifica, 10000);
    </script>

</body>
</html>
