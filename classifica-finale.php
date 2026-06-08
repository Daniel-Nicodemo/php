<?php

include 'db.php';


$colpevole_reale = 'Contessa Isabella';
$arma_reale = 'Veleno';

try{
$sql = "SELECT * FROM accuse ORDER BY data_invio ASC";
$stmt = $pdo->query($sql);
$accuse = $stmt->fetchAll();


} catch (PDOException $e){
    die("Errore nel recupero dati: " . $e->getMessage());

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classifica Finale</title>

<style>
        

        body { 
            font-family: 'Courier New', Courier, monospace; 
            background: #121212; 
            color: #e0e0e0; 
            padding: 15px; 
            line-height: 1.6;
        }

        h1 { 
            text-align: center; 
            color: #ff4d4d; 
            text-transform: uppercase; 
            border-bottom: 2px solid #8b0000;
            padding-bottom: 10px;
        }

        .tabella-container { 
            overflow-x: auto; 
            margin-top: 20px; 
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            background: #1e1e1e; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid #333; 
        }

        th { 
            background: #8b0000; 
            color: white; 
            text-transform: uppercase;
            font-size: 0.85em;
        }
        
        /* Stile per chi ha indovinato */
        .vincitore { 
            background: rgba(76, 175, 80, 0.2) !important; 
        }
        
        .rank { font-weight: bold; color: #ffcc00; }

        .movente-text { 
            font-style: italic; 
            font-size: 0.85em; 
            color: #bbb; 
            display: block; 
            margin-top: 5px; 
        }
        .status-icon { font-size: 1.2em; }

        .timestamp { font-family: 'Courier New', monospace; color: #888; font-size: 0.9em; }

        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background: #8b0000;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>


</head>
<body>

    <h1>Risultati investigazione</h1>

    <div class="tabella-container">

    <table>

        <thead>

            <tr>
                <th>Posizione</th>
                <th>Squadra</th>
                <th>Verdetto</th>
                <th>Ora invio</th>
            </tr> 


        </thead>

        <tbody>

            <?php

                $pos = 1;

                foreach ($accuse as $a):
                  $is_correct = ($a['colpevole'] === $colpevole_reale && $a['arma'] === $arma_reale);  
                  
                  $ora_invio = date("H:i:s", strtotime($a['data_invio']))
                  // echo strtotime("2025-03-31") = 1743379200

            ?>

            <tr class="<?= $is_correct ? 'vincitore' : '' ?>">

                <td class="rank"><?= $pos++  ?></td>

                <td>
                    <strong> <?= htmlspecialchars($a['tavolo_id']) ?> </strong>
                    <span class="movente-txt"> <?= htmlspecialchars($a['movente']) ?> </span>

                </td>



                <td>

                    <span class="status-icon"> <?= $is_correct ? '✔️' : '❌' ?> </span>
                    <br>
                    <small><?= htmlspecialchars($a['colpevole']) ?></small>


                </td>

                <td class="timestamp">
                    <?= $ora_invio ?>
                </td>


            </tr>

            <?php endforeach; ?>



            <?php  if (empty($accuse)):   ?>

                <tr>
                    <td colspan="4">
                        Nessuna a accusa pervenuta fin'ora.
                    </td>

                </tr>


            <?php endif; ?>    


        </tbody>


    </table>



    </div>
    
</body>
</html>