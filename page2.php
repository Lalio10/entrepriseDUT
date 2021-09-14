<?php
require_once "autoload.inc.php";

/////////////////////       Création de la page         ////////////////////
    $p = new WebPage("Utilisateur connecté");
/////////////////////       Ajout URL de fichier        ////////////////////
    $p->appendCssUrl("css/New.scss");
/////////////////////       Si Connecter                ////////////////////
    if(User::isConnected())
    {
        $user = User::createFromSession();
        $logoutForm = User::logoutForm("formSHA512.php", "Déconnexion");
        $content =<<<HTML
        <body>
            <nav>
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
                                <li><a href="http://localhost:84/dashboard/etapeFinal/clientPage.php"> Client </a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/nSeriesPage.php"> N° de série</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/processorPage.php"> Processeur</a></li>
                                <li><a href="http://localhost:84/dashboard/etapeFinal/systemeExpPage.php"> Système d'Exploitation</a></li>
                                </ul>
                            </li>
                            <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/pageCarte.php"> Carte</a></li>                            

                        </ul>
                    </nav>
            </nav>
        </body>
        <div class="card">
            <h1>Bienvenue vous êtes connecté !</h1>
            {$user->profile()}
            $logoutForm
            
        </div>
        HTML;
        $p -> appendContent($content); 
    }
/////////////////////       Pas connecter               /////////////////////
    else
        header("Location: http://localhost:84/dashboard/etapeFinal/formSHA512.php");
/////////////////////       Affichage du HTML           /////////////////////   
    echo $p->toHTML();