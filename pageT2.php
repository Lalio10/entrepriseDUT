<?php
require_once "autoload.inc.php";

/////////////////////       Création de la page         ////////////////////
    $p = new WebPage("Tableaux");
/////////////////////       Connexion BD               ////////////////////
    try 
    {  
        $conn = new PDO( );   
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
    }  
    catch( PDOException $e ) 
     {  
        die( "Error connecting to SQL Server" ); 
    } 
     
/////////////////////       Création de la page         ////////////////////
    $bds= new BDS();
/////////////////////       Ajout URL de fichier        ////////////////////
    $p->appendCssUrl("bootstrap/css/bootstrap.css");
    
    $p->appendJsUrl("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js");
    $p->appendJsUrl("js/jss.js");
    $p->appendCssUrl("css/New.scss");
/////////////////////       Si  Connecter               ////////////////////
    if(User::isConnected())
    { 
        /////////////////////Nouvelle nav bar////////////////////
            $p->appendContent(<<<HTML

            <div class='main' id='mainCont'>
                <div class="loader">
                    <img class="tourne" src="img/logofinal2.png" />
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

                <header>

                <nav>
                        <ul>
                            <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/index.php">Accueil</a></li>
                            <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/pageLoadGraph.php"> Graphiques</a></li>
                            <li class="menu">
                                <a>INFOS</a>
                                <ul class="ani-menu">
                                <li><a href="http://localhost:84/dashboard/etapeFinal/pageT.php"> Tableaux </a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/biosPage.php"> BIOS</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/clientPage.php"> Client </a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/nSeriesPage.php"> N° de série</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/processorPage.php"> Processeur</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/systemeExpPage.php"> Système d'Exploitation</a></li>
                                </ul>
                            </li>
                            <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/pageCarte.php"> Carte</a></li>                            
                            <li class="menu"><a href ="http://localhost:84/dashboard/etapeFinal/page2.php">Compte</a></li>

                        </ul>
                    </nav>
                   
                </header>
                <!-- /////////////////////Ajout des tableaux //////////////////// -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="aff">
                                        <h3 class="card-title">Infos ordinateur</h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Numéro fictif de l'ordinateur </th>
                                                    <th> Nom de l'ordinateur </th>
                                                    <th> Le type de cas </th>
                                                    <th> AssignedSite </th>
                                                    <th> Nom du site AD </th>
                                                    <th> Dernière date de Redémarrage  </th>
                                                    <th> Dernier redémarrage </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getDELLMachinInfosComputer()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="aff">
                                        <h3 class="card-title">Espace de stockage</h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Numéro fictif de l'ordinateur </th>
                                                    <th> Mémoire Totale </th>
                                                    <th> Memoire Total RAM (GB) </th>
                                                    <th> Memoire Total HDD (GB) </th>
                                                    <th> Dernière date de scan </th>
                                                    <th> Nombre de scan HW </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getDELLMachinMemory()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!--<button id="btnExportToCsv"-->
                    </div>
                <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                
            </div>
            
        HTML
        );
    }

/////////////////////       Pas connecter               /////////////////////
    else
    {
        header("Location: http://localhost:84/dashboard/etapeFinal/formSHA512.php");
    }
/////////////////////       Affichage du HTML           /////////////////////
    echo $p->toHTML();