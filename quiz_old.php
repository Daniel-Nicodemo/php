<?php

session_start();
include "db.php";

//sicurezza

if(!isset($_SESSION['tavolo_id'])){

    header("Location: index.php");
    exit;

}

//inizializzazione tavolo

$tavolo = $_SESSION['tavolo_id'];
$portata = $_GET['portata'] ?? '';

//whitelist delle portate ammesse al gioco

$portate_valide = ['antipasto', 'primo', 'secondo', 'dolce'];

//in_array controlla se l'array esiste

if(!in_array($portata, $portate_valide)){

    header("Location: dashboard.php");
    exit;

}

//1 - controllo pre-requisiti

$prerequisiti = [

    'antipasto' => null, //nessun prerequisito per questo caso
    'primo' => 'antipasto',
    'secondo' => 'primo',
    'dolce' => 'secondo'

];

// pesca il prerequsiito portata dall'url

$prerequisito = $prerequisiti[$portata];

// se il prerequisito e null saltiamo il controllo

if($prerequisito !== null){

    //contiamo quante righe esistono per la portata del prerequisito

    $check = $pdo->prepare("SELECT COUNT(*) FROM progressi_gioco WHERE tavolo_id = ? AND indizio_chiave = ?");
    $check->execute([$tavolo, $prerequisito]);

    /* 
        se non ci sono righe, rispedisci alla dashboard.
        Blocco per chi tenta: quiz.php?portata=dolce
        senza aver completato le portare precedenti
    */

    if($check->fetchColumn() == 0){

        header("location: dashboard.php");
        exit;
    }


}

//2 - controllo sblocco

/* 
scenario tipico: l'utente ha già risposto correttamente al quiz dell'antipasto, ma preme 'indietro del browser o riscrive a mano l'url quiz.php?portata=antipasto

*/

$gia_sbloccato = $pdo->prepare("SELECT COUNT(*) FROM progressi_gioco WHERE tavolo_id = ? AND indizio_chiave = ?");
$gia_sbloccato->execute([$tavolo, $portata]);

if($gia_sbloccato->fetchColumn() > 0){

    header("Location: indizio_$portata.php");
    exit;
}

//3. Caricamento dati da json: distinzione tra domande viste e domande disponibili
//dopo aver caricato il json
// il primo step è distinguere tra domande viste e domande disponibili

//fa il get contents, carica tutte le domande dal file json ... e non può scorrere le domande infatti ha bisogno di diventare un array

$json = file_get_contents('domande.json');

//json_decode trasforma il file json in un array associativo

$tutte = json_decode($json, true);

//domande gia viste

//gia viste conterra gli id delle domande gia mostrate per quella portata

/*
$gia viste conterra gli id delle domande gia mostrate per quella portata
$_session['domande_viste'] = [

        'antipasto'  => [1, 7],
        'primo' => [], //non ha ancora iniziato il primo

]
*/

$gia_viste = $_SESSION['domande_viste'][$portata] ?? [];

//creazione array vuoto per domande disponibili

$disponibili = [];

//scorre tutte le domande caricate dal json e carica le domande giuste

foreach($tutte as $d){

    //se non sono presenti aggiunge la domanda a disponibili

    if(!in_array($d['id'], $gia_viste)){

        $disponibili[] = $d;

    }

}

//se le abbiamo viste tutte, azzera e riparte
if(empty($disponibili)){

    $_SESSION['domande_viste'][$portata] = [];
    $disponibili = $tutte;
}

//generazione random di una domanda

//array_values ripristina l'array da zero in modo sequenziale

$disponibili = array_values($disponibili);
$indice = array_rand($disponibili);
$domanda = $disponibili[$indice]; //singola domande del json

//riferimento alle opzioni di ogni singola domanda

$opzioni = $domanda['opzioni'];

shuffle($opzioni);

// etichette per l'interfaccia
//array associativo per scrivere la portata in un italiano corretto

$etichette = [

    'antipasto' => 'Antipasto',
    'primo' => 'Primo piatto',
    'secondo' => 'Secondo piatto',
    'dolce' => 'Dolce'

];

$etichetta_portata = $etichette[$portata];

/* gestione della risposta da sviluppare

la logica è: 

-se la risposta e giusta: accedi alla pagina con il nuovo indizio.

-se la risposta e sbagliata deve caricare una nuova domanda.

*/

$feedback = '';

$corretta = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $risposta_utente = $_POST['risposta'] ?? '';

    $risposta_giusta = $_POST['risposta_giusta'] ?? '';

    //risposta giusta

    if($risposta_utente === $risposta_giusta){

    $sql = "INSERT IGNORE INTO progressi_gioco (tavolo_id, indizio_chiave) VALUES (?,?)";
    $pdo->prepare($sql)->execute([$tavolo, $portata]);
    header("Location: indizio_$portata.php");
    exit;
    }

    //risposta sbagliata
    //segnare la domanda come vista e mostrarne un'altra

    else {

        //prende via posto l'id della domanda
        $id_sbagliata = (int)$_POST['id_domanda'];

        //controllo di inizializzazione: serve a evitare un errore di php  

        if (!isset($_SESSION['domande_viste'][$portata])){

            $_SESSION['domande_viste'][$portata] = [];

        }

        //incremento l'array delle domande viste per portata con l'id sbagliato

        $_SESSION['domande_viste'][$portata] = $id_sbagliata;

        $feedback = 'Risposta errata. Prova con altro indizio gastronomico...';

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
            <div class="alert alert-error">
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
