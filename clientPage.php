<?php
require_once "autoload.inc.php";

/////////////////////       Création de la page         ////////////////////
    $p = new WebPage("Client");
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
                            <li><a href="http://localhost:84/dashboard/etapeFinal/pageT2.php"> Infos ordinateur</a></li>
                            <li><a href="http://localhost:84/dashboard/etapeFinal/biosPage.php"> BIOS</a></li>
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
                                        <h3 class="card-title"> Nom de domaine et de l'utilisateur </h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Numéro fictif de l'ordinateur </th>
                                                    <th> Nom du Domaine </th>
                                                    <th> Nom de l'Utilisateur </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getDELLMachinInfosClientDomainName()}
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
                                        <h3 class="card-title">Autres informations </h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Numéro fictif de l'ordinateur </th>
                                                    <th> Email </th>
                                                    <th> Role </th>
                                                    <th> ServicePack </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getDELLMachinInfosClientEmail()}
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
                                        <h3 class="card-title"> Version  </h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Numéro fictif de l'ordinateur </th>
                                                    <th> Version </th>
                                                    <th> Version du client </th>
                                                    <th> Date de création du client </th>
                                                    <th> Nombre de création </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getDELLMachinInfosClientVersion()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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