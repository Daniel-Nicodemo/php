<?php

$host = 'localhost';
$db = 'pranzo';
$user = 'root';
$pass = '';

// aggiunta per non far stampare con print_r un duplicato di valori
// (valore numerico della colonna e nome colonna)
$options = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Forza solo i nomi delle colonne
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
];


// charset=utf8mb4 serve a leggere simboli particolari nella password o nome db
$conn = "mysql:host=$host;dbname=$db;charset=utf8mb4";


// aggiunto $options nell'oggetto pdo
try {
    $pdo = new PDO($conn, $user, $pass, $options);
    // stampa ulteriori errori
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'Connessione avvenuta con successo!';

} catch (PDOException $e){

    die("Errore di connessione al DB: " . $e->getMessage());


}



