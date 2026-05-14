<?php

session_start();
include "db.php";

if(!isset($_SESSION["tavolo_id"])){

    header("Location: index.php");
    exit;
}

$tavolo_id = $_SESSION["tavolo_id"];

//cancella i progressi di gioco per questo investigatore
$stmt = $pdo->prepare("DELETE FROM progressi_gioco WHERE tavolo_id = ?");
$stmt->execute($tavolo_id);

//cancella le accuse dal db per questo investigatore
$stmt = $pdo->prepare("DELETE FROM accuse WHERE tavolo_id = ?");
$stmt->execute($tavolo_id);


//svuota le sessioni
$_SESSION = [];
//distrugge il file
session_destroy();

header("Location: index.php");
exit;

?>