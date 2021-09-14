<?php
require_once "autoload.inc.php";

/////////////////////Création de la page ////////////////////
    $p = new WebPage("Accueil");
////////////////////////Connection BD////////////////////
    try {  
    $conn = new PDO( );   
    $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
 }  
   
 catch( PDOException $e ) {  
    die( "Error connecting to SQL Server" ); 
 }  
/////////////////////Création de la classe BDS////////////////////
    $bds= new BDS();
/////////////////////Ajout URL de fichier ////////////////////
    $p->appendCssUrl("css/New.scss");
/////////////////////Si Connecter////////////////////
    if(User::isConnected())
    {
        $p->appendContent(<<<HTML
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
            <script src="https://unpkg.com/swup@latest/dist/swup.min.js"></script>
            
                <nav>
                    <ul> 
                        <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/index.php">Accueil</a></li>
                        <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/pageLoadGraph.php"> Graphiques</a></li>
                        <li class="menu">
                            <a href ="#">INFOS</a>
                            <ul class="ani-menu">
                                <li><a href="http://localhost:84/dashboard/etapeFinal/pageT.php"> Tableaux </a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/pageT2.php"> Infos ordinateur</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/biosPage.php"> BIOS</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/clientPage.php"> Client </a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/nSeriesPage.php"> N° de série</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/processorPage.php"> Processeur</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/systemeExpPage.php"> Système d'Exploitation</a></li>
                                <li>
                            </ul>
                        </li>
                        <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/pageCarte.php"> Carte</a></li>
                        <li class="menu"><a href ="http://localhost:84/dashboard/etapeFinal/page2.php">Compte</a></li>
                    </ul>
                </nav>           
        HTML
        );
        
    /////////////////TITRE ET GRAPHIQUE/////////
    $p->appendContent(<<<HTML
                <!-- TITRE   -->
                <div class="card">
                    <h1>Accueil</h1>      
                    Bonjour à vous, bienvenu sur votre page web 
    HTML
    );
    }

//////////////////////////PAS CONNECTE/////////////////////
    else{
        $p->appendContent(<<<HTML
        <body>
            <nav>
                <ul>
                    <li class="menu"><a href ="http://localhost:84/dashboard/etapeFinal/formSHA512.php">Formulaire de connexion</a></li>
                </ul>
            </nav>
        </body>
        <!-- TEXT    -->
        <div class="card">
                    <h1>Accueil</h1>
                Bonjour à vous, bienvenu sur votre page web 
    HTML
    );
    }
////////////////////////// Affichage du HTML/////////////////////
    echo $p->toHTML();
