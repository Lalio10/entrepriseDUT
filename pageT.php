<?php
require_once "autoload.inc.php";

/////////////////////       Création de la page         ////////////////////
    $p = new WebPage("Tableaux");
/////////////////////       Connection BD               ////////////////////
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
/////////////////////       Ajout URL de fichier        ///////////////////
    $p->appendCssUrl("bootstrap/css/bootstrap.css");
    $p->appendJsUrl("bootstrap/js/bootstrap.js");
    $p->appendJsUrl("https://code.jquery.com/jquery-3.2.1.slim.min.js");
    $p->appendJsUrl("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js");
    
    
    $p->appendJsUrl("js/jss.js");
   
/////////////////////       Si  Connecter               ////////////////////
    if(User::isConnected())
    { 
        //   /////////////////////       Nouvelle nav bar       ////////////////////
            $p->appendContent(<<<HTML
            <div class='main' id='mainCont'>
                <div class="loader">
                    <img class="tourne" src="img/logofinal2.png" />
                </div>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <link rel="stylesheet" type="text/css" href="css/New.scss">
            
                <header>

                    <nav>
                        <ul>
                            <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/index.php">Accueil</a></li>
                            <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/pageLoadGraph.php"> Graphiques</a></li>
                            <li class="menu">
                                <a>INFOS</a>
                                <ul class="ani-menu">
                                <li><a href="http://localhost:84/dashboard/etapeFinal/pageT2.php"> Infos ordinateur</a></li>
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
        <!-- /////////////////////      Ajout des tableaux      //////////////////// -->
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="aff">
                                        <h3 class="card-title">Catégorie et Nombre de poste</h3>
                                        <table class="table table-striped" id="dataTable0">
                                            <thead>
                                                <tr>
                                                    <th>Catégorie</th>
                                                    <th> Nombre de poste</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getAllNameMemberCount()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button id="btn0" type="button" class="button"> Export to CSV </button>
                        </div>
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="aff">
                                        <h3 class="card-title">Nombre de poste par composante</h3>
                                        <table class="table table-striped" id="dataTable1">
                                            <thead>
                                                <tr>
                                                    <th>Nom de la composante</th>
                                                    <th> Nombre de machine</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getComponent()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button id="btn1" type="button" class="button"> Export to CSV </button>
                        </div>
                        
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="aff">
                                        <h3 class="card-title">Nom du poste et groupe auquel il appartient </h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Nom </th>
                                                    <th> Identifier unique </th>
                                                    <th> WorkGroup </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getAllChassis(9)}
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
                                        <h3 class="card-title">Statuts des points de déploiements</h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Nom du serveur </th>
                                                    <th> Le status </th>
                                                    <th> Total </th>
                                                    <th> Progression </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getPointOfDeployementsStatus('SMS Distribution Point')}
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
                                        <h3 class="card-title">Demande d'installations d'application en attente </h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Catégorie </th>
                                                    <th> Durée </th>
                                                    <th> Catégorie d'installation </th>
                                                    <th> Tous les ports d'installation </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getPendingApplicationInstallRequest()}
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
                                        <h3 class="card-title">Statuts des clients</h3>
                                        <table class="table table-striped" id="dataTable2">
                                            <thead>
                                                <tr>
                                                    <th> Statut client </th>
                                                    <th> Description du statut </th>
                                                    <th> Nombre total de client </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getClientStatus()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button id="btnExportToCsv" type="button" class="button"> Export to CSV </button>
                        </div>

                        

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="aff">
                                        <h3 class="card-title">Adobe Acrobat</h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Nom </th>
                                                    <th> Nom du produit </th>
                                                    <th> Version </th>
                                                    <th> Installation </th>
                                                    <th> Chemin </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getAcrobatAdobeMachin()}
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
                                        <h3 class="card-title">Statuts des sites </h3>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th> Site </th>
                                                    <th> N° du statut </th>
                                                    <th> Mis à jour </th>
                                                    <th> Etat </th>
                                                    <th> Statut </th>
                                                    <th> Statut final </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {$bds->getStatusSite()}
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!--<button id="btnExportToCsv"-->
                        </div>

                    </div>
                <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                <script>
                    /**
                     * Reprise d'élement en HTML sous javascript
                     */
                        let dataTable = document.getElementById("dataTable0");
                    
                    //console.log(new TableCSVExporter(dataTable, false ).convertToCSV());
                        let btnExportToCsv = document.getElementById("btn0");
                    /**
                     * Création d'un évènement de type click permettant la conversion d'un tableau en HTML en CSV (extension EXCEL) 
                     */
                        btnExportToCsv.addEventListener("click", ()=>{
                            const exporter = new TableCSVExporter(dataTable);
                            const csvOutput = exporter.convertToCSV();
                            /**
                             * Création d'un fichier de type CSV
                             */
                                const csvB = new Blob([csvOutput], {type : "text/csv"});
                            /**
                             * Création du lien qui permet d'aller au fichier
                             */
                                const blobURL = URL.createObjectURL(csvB);
                                const anchorElements= document.createElement("a");
                                anchorElements.href = blobURL;
                            /**
                             * Création du nom du fichier   
                             */
                                anchorElements.download= "table-export.csv";
                            anchorElements.click();

                            setTimeout(() => 
                            {
                                URL.revokeObjectURL(blobURL);
                            },500);
                        });
                </script>
                <script>
                                
                    let dataTable1 = document.getElementById("dataTable1");
                                
                    //console.log(new TableCSVExporter(dataTable, false ).convertToCSV());
                    let btnExportToCsv1 = document.getElementById("btn1");
                    btnExportToCsv1.addEventListener("click", ()=>
                    {
                        const exporter = new TableCSVExporter(dataTable1);
                        const csvOutput = exporter.convertToCSV();
                        const csvB = new Blob([csvOutput], {type : "text/csv"});
                        const blobURL = URL.createObjectURL(csvB);
                        const anchorElements= document.createElement("a");
                        
                        
                        anchorElements.href = blobURL;
                        anchorElements.download= "table-export.csv";
                        anchorElements.click();

                        setTimeout(() => {
                            URL.revokeObjectURL(blobURL);
                        },500);
                    });
                    </script>
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