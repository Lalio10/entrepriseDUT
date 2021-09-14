<?php
require_once "autoload.inc.php";
$p = new WebPage('Authentification') ;

try {
    // Tentative de connexion
    $user = User::createFromAuth512($_REQUEST) ;
    $user->saveIntoSession();
    header("Location: page2.php");
    
    $user->profile();

}
catch (AuthentificationException $e) {
    // Récupération de l'exception si connexion échouée
    $p->appendContent("Échec d'authentification&nbsp;: {$e->getMessage()}") ;
}
catch (SessionException $e)
{
    $p->appendContent($e->getMessage());
}
catch (Exception $e) {
    $p->appendContent("Un problème est survenu&nbsp;: {$e->getMessage()}") ;
}
echo $p->toHTML();


