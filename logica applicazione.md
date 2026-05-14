#  PRANZO CON DELITTO - Gioco da casa


## index.php

pagina di benvenuto  con trama, gli utenti hanno un'identità Guest con nomi narrativi, link diretto al quiz dell'antipasto:

<a href="quiz.php?portata=antipasto" class="btn">



## quiz.php 

Questa versione del gioco non e più necessaria unlock.php

Fa tantissime cose:

1. Controlla i pre-requisiti: si può accedere alla domande che consente di sbloccare l'indizio solo se abbiamo sbloccato l'indizio precedente.

2. Controllo sblocco: se l'indizio è già sbloccato non ripresentiamo il quiz ma mandiamo l'utente 

3. Caricamento dati da json: distinzione tra domande viste e domande disponibili

4. generazione random della domanda

5. gestione della risposta

## grazie-investigatore.php

il file grazie-investigatore.php deve: 

1) Riepilogare l'accusa fatta dall'investigatore
2) Confrontare l'accusa con la soluzione del caso
3) Mostrare un messaggio "hai indovinato" o "non hai indovinato", nel caso non abbia indovinato fa ricominciare il gioco
