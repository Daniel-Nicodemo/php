
<?php
session_start();
include 'db.php';

// Verifichiamo che il tavolo sia identificato
if (!isset($_SESSION['tavolo_id'])) {
    die("Sessione scaduta o tavolo non identificato.");
}

$tavolo_id = $_SESSION['tavolo_id']; // Es: "Tavolo Rosso" o "Tavolo_123"
$colpevole = $_POST['colpevole'] ?? '';
$arma = $_POST['arma'] ?? '';
$movente = $_POST['movente'] ?? '';

try {
    $sql = "INSERT INTO accuse (tavolo_id, colpevole, arma, movente) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$tavolo_id, $colpevole, $arma, $movente]);
    
    //versione non sicura:
    //$sql = "INSERT INTO accuse (tavolo_id, colpevole, arma, movente) VALUES ($tavolo_id, $colpevole, $arma, $movente)";
    //$pdo->exec($sql);

    //e una prova di intezione di sql (sql injection)
    //perche si'),('Ispettore_leblanc_30', 'Tchurs', 'coltello', 'prova')
    //altro esempio di sql injection: movente l'arrabbiatura

    // Successo: reindirizziamo per i ringraziamenti
    header("Location: grazie-investigatore.php");
    exit;

} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Codice errore per "Duplicate entry"
        echo "<h1>Verdetto già inviato!</h1><p>Non puoi cambiare la tua accusa. La giustizia farà il suo corso.</p>";
    } else {
        echo "Errore tecnico: " . $e->getMessage();
    }
}