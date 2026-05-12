<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
<p>
<?php

    if($_POST["colpevole"] === "Elena"){
        echo "$_POST['colpevole']"; //questa e la risposta corretta

    } elseif($_POST["colpevole"] != "Elena"){

        echo "$_POST['colpevole']"; //questa e la risposta sbagliata

    }

?>
</p>

<p>
<?php

    if($_POST["arma"] === "Veleno"){
        echo "$_POST['arma']"; //questa e la risposta corretta

    } elseif($_POST["arma"] != "Veleno"){

        echo "$_POST['arma']"; //questa e la risposta sbagliata

    }

?>
</p>

</body>
</html>