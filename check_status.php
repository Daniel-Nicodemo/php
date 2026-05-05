<?php

include 'db.php';

$stmt = $pdo->query("SELECT COUNT(*) FROM accuse");

// creazione variabile 'pubblicata'

$pubblicata = ($stmt->fetchColumn() > 0);

//{'pubblicata': true}

echo json_encode(['pubblicata' => $pubblicata]);