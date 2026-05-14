<?php

session_start();
include "db.php";

try{

$stmt = $pdo->prepare("SELECT colpevole, arma FROM accuse WHERE tavolo_id = ?");
$stmt->execute([$tavolo]);
$sbloccati = $stmt->fetchAll(PDO::FETCH_COLUMN);

} catch {



}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>Grazie investigatore <?= htmlspecialchars($tavolo_attuale) ?></h1>

<p>
<?php

    if($_POST["colpevole"] === "Elena"){
        echo '$_POST["colpevole"]'; //questa e la risposta corretta

    } elseif($_POST["colpevole"] != "Elena"){

        echo '$_POST["colpevole"]'; //questa e la risposta sbagliata

    }

?>
</p>

<p>
<?php

    if($_POST["arma"] === "Veleno"){
        echo '$_POST["arma"]'; //questa e la risposta corretta

    } elseif($_POST["arma"] != "Veleno"){

        echo '$_POST["arma"]'; //questa e la risposta sbagliata

    }

?>
</p>

<a href="index.php"> vuoi ricominciare? </a>

</body>
</html>