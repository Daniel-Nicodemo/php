<?php
session_start();
include "db.php";
if (!isset($_SESSION["tavolo_id"])){
    header("Location: index.php");
}
$tavolo_id = $_SESSION["tavolo_id"];

//cancella i dati per questo investigatore
$stmt = $pdo->prepare("DELETE FROM progressi_gioco WHERE tavolo_id = ?");
$stmt->execute([$tavolo_id]);
//cancella l'accusa dal db
$stmt = $pdo->prepare("DELETE FROM accuse WHERE tavolo_id = ?");
$stmt->execute([$tavolo_id]);

$_SESSION = [];
session_destroy();

header("Location: index.php");
exit;