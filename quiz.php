<?php

session_start();
include 'db.php';



if(!isset($_SESSION['tavolo_id'])){

    header("Location: index.php");
    exit;


}

$tavolo = $_SESSION['tavolo_id'];
$portata = $_GET['portata'] ?? '';


// Whitelist delle portate ammesse al gioco
$portate_valide = ['antipasto', 'primo', 'secondo', 'dolce'];

if (!in_array($portata, $portate_valide)){

    header("Location: dashboard.php");
    exit;

}


// 1 - Controllo prerequisiti

$prerequisiti = [

    'antipasto' => null, // nessun prerequisito per questo caso
    'primo'     => 'antipasto',
    'secondo'   => 'primo',
    'dolce'     => 'secondo'


];


// pesca il prerequisito portata dall'url

$prerequisito = $prerequisiti[$portata];

// se il prerequisito è null saltiamo il controllo

if ($prerequisito !== null){

    // contiamo quante righe esistono per la portata del prerequisito

    $check = $pdo->prepare("SELECT COUNT(*) FROM progressi_gioco WHERE tavolo_id = ? AND indizio_chiave = ?");
    $check->execute([$tavolo, $prerequisito]);


    /*
    se non ci sono righe, rispedisci alla dashboard.
    Blocco per chi tenta: quiz.php?portata=dolce
    senza aver completato le portate precedenti.

    */

    if ($check->fetchColumn() == 0){

        header("Location: dashboard.php");
        exit;

    }

}


// 2. CONTROLLO SBLOCCO

/*
scenario tipico: l'utente ha già risposto correttamente al quiz dell'antipasto, ma preme 'indietro' del browser o 

riscrive a mano l'url quiz.php?portata=antipasto

*/

$gia_sbloccato = $pdo->prepare("SELECT COUNT(*) FROM progressi_gioco WHERE tavolo_id = ? AND indizio_chiave = ?");
$gia_sbloccato->execute([$tavolo, $portata]);

if($gia_sbloccato->fetchColumn() > 0){

    header("Location: indizio_$portata.php");
    exit;


}


// 3. dati da JSON: distinzione tra domande viste e domande disponibili

// Dopo aver caricato il JSON
// il primo step è distinguere tra domande viste e domande disponibili

//echo $tutte[0]['id']


$json   = file_get_contents('domande.json');
$tutte  = json_decode($json, true);

// domande già viste

/*

$gia_viste contiene gli ID delle domande già mostrate per quella portata



$_SESSION['domande_viste'] = [

    'antipasto' => [1, 7],
    'primo'     => [], // non ha ancora iniziato il primo

]

*/


$gia_viste  = $_SESSION['domande_viste'][$portata] ?? [];



$disponibili = [];


foreach ($tutte as $d){


    if (!in_array($d['id'], $gia_viste)){

        $disponibili[] = $d;

    }



}


// se le abbiamo viste tutte, azzera e riparte

if (empty($disponibili)){

    $_SESSION['domande_viste'][$portata] = [];
    $disponibili = $tutte;


}


// 4. GENERAZIONE RANDOM DI UNA DOMANDA

//array_values ripristina l'array da zero in modo sequenziale
$disponibili = array_values($disponibili); 
$indice      = array_rand($disponibili);
$domanda     = $disponibili[$indice]; // $domanda è la singola domanda nel JSON


// riferimento alle opzioni di ogni singola domanda
$opzioni = $domanda['opzioni'];

shuffle($opzioni);


// Etichette per l'interfaccia
// Array associativo per scrivere la portata in italiano corretto!

$etichette = [

    'antipasto' => 'Antipasto',
    'primo'     => 'Primo Piatto',
    'secondo'   => 'Secondo Piatto',
    'dolce'     => 'Dolce'




];


$etichetta_portata = $etichette[$portata];

/* 5. GESTIONE DELLA RISPOSTA (da sviluppare)

La logica è:

- se la risposta è giusta: accedi alla pagina con il nuovo indizio.

- se la risposta è sbagliata deve caricare una nuova domanda.



*/


$feedback = '';
$corretta = false;


if ($_SERVER['REQUEST_METHOD'] === 'POST'){


    $risposta_utente = $_POST['risposta'] ?? '';

    $risposta_giusta = $_POST['risposta_giusta'] ?? '';


    // risposta giusta

    if ($risposta_utente === $risposta_giusta){


            $sql = "INSERT IGNORE INTO progressi_gioco (tavolo_id, indizio_chiave) VALUES (?, ?)";
            $pdo->prepare($sql)->execute([$tavolo, $portata]);
            header("Location: indizio_$portata.php");
            exit;



    }

    // Risposta sbagliata
    // segnare la domanda come vista e mostrarne un'altra

    else {

        // prende via post l'id della domanda sbagliata
        $id_sbagliata = (int)$_POST['id_domanda'];


        if (!isset($_SESSION['domande_viste'][$portata])){

            $_SESSION['domande_viste'][$portata] = [];


        }

        // Incremento l'array delle domande viste per portata con l'id sbagliato
        $_SESSION['domande_viste'][$portata][] = $id_sbagliata;



        $feedback = 'Risposta errata. Prova con altro indizio gastronomico..';


    }



}






?>




<!-- view. per esempio: quiz.php?portata=antipasto -->


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prova: <?= $etichetta_portata ?></title>
    <link rel="stylesheet" href="pico-main/css/pico.min.css">
</head>
<body>

    <div class="container">

        <nav>
            <a href="dashboard.php" class="back-link">⬅ Torna al Taccuino</a>
        </nav>

        <h1>🍽️ Prova dell'<?= $etichetta_portata ?></h1>
        <p>Rispondi correttamente per sbloccare il prossimo indizio.</p>

        <?php if ($feedback): ?>
            <div class="alert alert-error" style="color: red;">
                <?= htmlspecialchars($feedback) ?>
            </div>
        <?php endif; ?>




        <div class="quiz-card">

            <!-- Prende la domanda dal file JSON -->
            <p class="quiz-domanda"><strong><?= htmlspecialchars($domanda['domanda']) ?></strong></p>

            <form method="POST" action="quiz.php?portata=<?= $portata ?>">

                <!-- Passiamo l'ID della domanda e la risposta corretta come campi nascosti
                    (che prende sempre dal JSON)
                -->
                <input type="hidden" name="id_domanda"     value="<?= $domanda['id'] ?>">
                <input type="hidden" name="risposta_giusta" value="<?= htmlspecialchars($domanda['corretta']) ?>">

                <div class="quiz-opzioni">

                    <!-- cicla all'interno delle opzioni del JSON e le stampa nel form -->
                    <?php foreach ($opzioni as $opzione): ?>
                        <label class="opzione">
                            <input type="radio" name="risposta" value="<?= htmlspecialchars($opzione) ?>" required>
                            <?= htmlspecialchars($opzione) ?>
                        </label>
                    <?php endforeach; ?>

                </div>
                <br>
                <button type="submit" class="btn">Conferma risposta</button>

            </form>
        </div>

    </div>

</body>
</html>