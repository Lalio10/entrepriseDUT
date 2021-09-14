<?php
require_once "autoload.inc.php";

/////////////////////       Création de la page         ////////////////////
    $p = new WebPage("Graphiques");
/////////////////////       Connexion BD               ////////////////////
    try {  
        $conn = new PDO( );   
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
        
    }  
    catch( PDOException $e ) 
    {  
        die( "Error connecting to SQL Server" ); 
    }  
/////////////////////       Création de la classe BDS   ////////////////////
    $bds= new BDS();
/////////////////////       Ajout URL de fichier        ////////////////////
    $p->appendCssUrl("bootstrap/css/bootstrap.css");
    $p->appendJsUrl("bootstrap/js/bootstrap.js");
    $p->appendJsUrl("https://code.jquery.com/jquery-3.2.1.slim.min.js");
    $p->appendJsUrl("https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js");
    
    $p->appendCssUrl("css/New.scss");
    $p->appendJsUrl("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js");
    $p->appendJsUrl("js/jss.js");
    
/////////////////////       Si  Connecter               ////////////////////
    if(User::isConnected())
    { 
        /////////////////////Nouvelle nav bar////////////////////
            $p->appendContent(<<<HTML
            <div class='main' id='mainCont'>
                <div class="loader">
                    <img class="tourne" src="img/logofinal2.png" />
                </div>
                
                <header>
                    
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
                    <script src="https://code.jquery.com/jquery-3.4.1.js"></script>
                <nav>
                    <ul> 
                        <li class="menu"><a href="http://localhost:84/dashboard/etapeFinal/index.php">Accueil</a></li>
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
                </header>
                <body>
                    <!-- TITRE   -->
                    <h1>Graphique </h1>
                    <!-- Graph  -->
                
                    <div class="row">
                        <div class="col-sm-4">
                        
                            <canvas class="card2" id="graph1"></canvas>
                            <script>
                                graph1({$bds->getUserAct(1)}, {$bds->getUserActN(0)});
                            </script>
                        </div>
                        <div class="col-sm-4">
                            <canvas class="card2" id="graph2"></canvas>
                            <script>
                                graph2({$bds->getMachinOnOff(1)}, {$bds->getMachinOnOff(0)});
                            </script>
                        </div>
                        <div class="col-sm-4">
                            <canvas class="card2" id="graph3"></canvas>
                            <script>
                                graph3({$bds->getAllWorktation()}, {$bds->getAllMicrosoft()}, {$bds->getAllOthers()});
                            </script>
                        </div>
                    
                    <div class="col-sm-4">
                        <canvas class="card2" id="graph4"></canvas>
                        <script>
                            graph4({$bds->getAllChassisWithNumber(8)}, {$bds->getAllChassisWithNumber(9)}, {$bds->getAllChassisWithNumber(10)}, {$bds->getAllChassisWithNumber(11)},
                                {$bds->getAllChassisWithNumber(12)}, {$bds->getAllChassisWithNumber(14)}, {$bds->getAllChassisWithNumber(18)}, {$bds->getAllChassisWithNumber(21)});
                        </script>
                    </div>
                </div>
                    
            </div>
            </body>
        HTML
            );


        ////////////////////CREATION DU GRAPHIQUE JS ///////////////
        
            function pourcentage($val1, $val2) {
                return round($val1 *100 / ($val1+$val2),2) ;
            }
            $division=pourcentage($bds->getMachinOnOff(1),$bds->getMachinOnOff(0));
            $division2=pourcentage($bds->getMachinOnOff(0),$bds->getMachinOnOff(1));
            ///Graph1
            $p->appendJs(<<<JS

                function  graph1 (data1,data2)
                {
                    var content = document.getElementById('graph1').getContext('2d')
                    var data = {
                        labels: ["Nombre d'utilisateur actif", "Nombre d'utilisateur inactif"],
                        datasets: [{
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 205, 86)',
                                'rgb(0,0,0)'
                                ],
                                data: [data1, data2]
                            }]
                        }
                    var config = 
                    {
                        type: 'pie',
                        data: data
                    }
                    var graph1 = new Chart(content, config);
                    return graph1;
                }
                JS
                );
            ///Graph2  
            $p->appendJS(<<<JS
                function  graph2 (data1,data2)
                {
                    var content = document.getElementById('graph2').getContext('2d')
                    var data = {
                        labels: ["Nombre de machine active ({$division}%) ","Nombre de machine Inactif ({$division2}%) "],
                        datasets: [{
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 205, 86)',
                                'rgb(0,0,0)'
                                ],
                                data: [data1, data2]
                            }]
                        }
                    var config = 
                    {
                        type: 'pie',
                        data: data
                    }
                        
                    var graph = new Chart(content, config);
                    return graph;
                }
                JS
            );
            ///Graph3
            $p->appendJs(<<<JS

                function  graph3 (data1,data2,data3)
                {
                    var content = document.getElementById('graph3').getContext('2d')
                    var data = {
                        labels: [" WorkStation", " Microsoft","Autres"],
                        datasets: [{
                            label:"Postes de travail ",
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 205, 86)',
                                'rgb(0,0,0)'
                                ],
                                data: [data1,data2,data3]
                            }]
                        }
                    
                    var config = 
                    {
                        type: 'bar',
                        data: data
                    }
                        
                    var graph3 = new Chart(content, config);
                    return graph3;
                }
                JS
            );
            ///Graph4  
            $p->appendJS(<<<JS
                function  graph4 (data1,data2,data3,data4,data5,data6,data7,data8)
                {
                    var content = document.getElementById('graph4').getContext('2d')
                    var data = {
                        labels: [   "Portatif ",
                                    "Ordinateur portable ",
                                    "Carnet de notes", 
                                    "Main tenue",
                                    "Station d'amarrage",
                                    "Sous-ordinateur portable",
                                    "Chassis d'expansion",
                                    "Chassis périphérique"],
                        datasets: [{
                            label:"Chassis ",
                            backgroundColor: [
                                'rgba(255, 0, 0)',
                                'rgba(0, 255, 0)',
                                'rgba(0, 0, 255)',
                                'rgba(0,0,0)',
                                'rgba(0,251,144)',
                                'rgba(255,255,0)',
                                'rgba(255,0,255)',
                                'rgba(255,138,255)'
                                ],
                                data: [data1,data2,data3,data4,data5,data6,data7,data8]
                            }]
                        }
                    var config = 
                    {
                        type: 'line',
                        data: data
                    }
                        
                    var graph = new Chart(content, config);
                    return graph;
                }
                JS
            );
    }

/////////////////////       Pas Connecter               ////////////////////
    else
    {
        header("Location: http://localhost:84/dashboard/etapeFinal/formSHA512.php");
    }
/////////////////////       Affichage du HTML           ////////////////////
    echo $p->toHTML();
