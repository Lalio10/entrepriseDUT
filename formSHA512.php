<?php
require_once('autoload.inc.php') ;
User::logoutIfRequested();
/////////////////////       Création de la page     ////////////////////
    $p = new WebPage('Authentification') ;
//////////////////////      Pas connecter           /////////////////////
    if(!User::isConnected())
    {
        // Production du formulaire de connexion
        $p->appendCssUrl("css/New.scss");
        $p->appendContent(<<<HTML
        
            <nav>
                <ul> 
                    <li class="menu"><a href ="http://localhost:84/dashboard/etapeFinal/index.php">Accueil</a></li>
                    
                </ul>
            </nav>

        <div class="card"> 
            
                <h1> Formulaire de connexion</h1>
                <h2> Veuillez-vous connecter !</h2>
            <div>
        HTML
        );
        $form = User::loginFormSHA512('authSHA512.php');
        $p->appendContent($form);
        $p->appendContent(<<<HTML
        </div>
        HTML    
        );
    }
/////////////////////       Si  Connecter           ////////////////////
    else{
        $user = User::createFromSession();
        $p->appendContent($user->profile());
        $p->appendContent(User::logoutForm("formSHA512.php", "Déconnexion"));
    }

/////////////////////////// Affichage du HTML       /////////////////////
    echo $p->toHTML();
