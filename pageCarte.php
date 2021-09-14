<?php
require_once "autoload.inc.php";

/////////////////////       Création de la page         ////////////////////
    $p = new WebPage("Carte");
/////////////////////       Connexion BD               ////////////////////
    try {  
        $conn = new PDO( );   
        $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );  
     }  
       
     catch( PDOException $e ) {  
        die( "Error connecting to SQL Server" ); 
     } 
     
/////////////////////       Création de la classe BDS   ////////////////////
    $bds= new BDS();
/////////////////////       Ajout URL de fichier        ////////////////////
    $p->appendCssUrl("bootstrap/css/bootstrap.css");
    $p->appendCssUrl("https://unpkg.com/leaflet@1.7.1/dist/leaflet.css");
    $p->appendJsUrl("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js");
    $p->appendJsUrl("js/jss.js");
    $p->appendJsUrl("https://unpkg.com/leaflet@1.7.1/dist/leaflet.js");
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
                            <li class="menu"><a href ="http://localhost:84/dashboard/etapeFinal/page2.php">Compte</a></li>
                        </ul>
                    </nav>
                </header>
                <h2>Carte </h2>
            <div id="mapid" style=" width: 100%; height: 52em;"></div>
        
        
           
                
                
            
        HTML
        );
    /////////////////////Ajout des tableaux ////////////////////
        $p->appendJs(<<<JS
            window.onload= function(){
                /////////// Création de la carte ///////////
                    var mymap = L.map('mapid').setView([49.244915, 4.064391], 8);

                //////////// Paramètre de la carte leaflet///////////
                    var mapUrl = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}';
                    var mapAttribution = 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>';
                    var mapToken ='pk.eyJ1IjoiemVybTAwMDIiLCJhIjoiY2tvNGU0aW81MDdkejJwczZhOXIyMzE0OSJ9.zk8aheiim4mk62N2QqcmoQ';
                
                /////////// Configuration de la carte ///////////
                    var tileStreets = L.tileLayer(mapUrl, {
                        attribution: mapAttribution ,
                        maxZoom: 18,
                        id: 'mapbox/satellite-streets-v11',
                        tileSize: 512,
                        zoomOffset: -1,
                        accessToken: mapToken
                        });
                        tileStreets.addTo(mymap);
                /////////// Direction du Numérique ///////////
                    var customIconC = L.icon({
                        iconUrl: 'Templatic-map-icons/computers.png',
                        iconSize :[25,40],
                        iconAnchor : [15,40],
                        popupAnchor: [-2, -35]
                    });   
                    var dn = L.marker([49.244915, 4.064391], {icon: customIconC}).addTo(mymap);
                    dn.bindPopup("<div>DN Centre de Service <div>Un total de {$bds->getAllSystem('CL-DN Centre de Service')} machines").openPopup();

                    var ip = L.marker([49.232862, 4.008271], {icon: customIconC}).addTo(mymap);
                    ip.bindPopup("<div>InfosProx Siège  <div>Un total de {$bds->getAllSystem('CL-InfoPROX Siège')} machines").openPopup();
                    var circle1 = L.circle([49.244915, 4.064391], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 50
                            }).addTo(mymap);
                    
                    var polygon = L.polygon([
                            [49.245494, 4.063471],
                            [49.243024, 4.058437],
                            [49.241058, 4.064861],
                            
                            [49.239323, 4.071898],
                            [49.243913, 4.070309]
                        ]).addTo(mymap);

                /////////// IUT ou Campus ///////////
                    var customIconSchool = L.icon({
                        iconUrl: 'Templatic-map-icons/schools.png',
                        iconSize :[25,40],
                        iconAnchor : [15,40],
                        popupAnchor: [-2, -35]
                    });    
                    var cMH = L.marker([49.24348603228111, 4.062409761955968], {icon: customIconSchool}).addTo(mymap);
                    cMH.bindPopup("<div>Campus Moulin de la Housse <div>Un total de {$bds->getAllSystem('CL-InfoPROX Moulin de la House')} machines").openPopup();

                    var iutR = L.marker([49.240436, 4.062450], {icon: customIconSchool}).addTo(mymap);
                    iutR.bindPopup("<div>IUT Reims <div>Un total de {$bds->getAllSystem('CL-InfoPROX IUT RCC')} machines").openPopup();
                    var polygon2 = L.polygon([
                            [49.241661, 4.061741],
                            [49.240145, 4.059750],
                            [49.238752, 4.063879],
                            
                            [49.240783, 4.064178],
                            [49.241083, 4.062802]
                        ]).addTo(mymap);
                    var eisine = L.marker([49.24230964358965, 4.062832601162114]).addTo(mymap);
                    eisine.bindPopup("<div>EiSINe <div>Un total de {$bds->getAllSystem('CL-InfoPROX EISINE')} machines").openPopup();

                    var iTheMM = L.marker([49.243255, 4.066241]).addTo(mymap);
                    iTheMM.bindPopup("<div>Institut de Thermique, Mécanique et Matériaux (ITheMM)  <div>Un total de {$bds->getAllSystem('CL-InfoPROX ITHEMM')} machines").openPopup();

                    var iutCharleville = L.marker([49.739979, 4.720011], {icon: customIconSchool}).addTo(mymap);
                    iutCharleville.bindPopup("<div>IUT Charleville <div>Un total de {$bds->getAllSystem('CL-InfoPROX IUT Charleville')} machines").openPopup();
                    
                    var polygon3 = L.polygon([
                            [49.740693, 4.720338],
                            [49.740104, 4.721636],
                            [49.739297, 4.720632],
                            
                            [49.739587, 4.719807],
                            [49.740235, 4.719775]
                        ]).addTo(mymap);
                    
                    
                    var iutChalon = L.marker([48.960021, 4.356833], {icon: customIconSchool}).addTo(mymap);
                    iutChalon.bindPopup("<div>IUT Châlon <div>Un total de {$bds->getAllSystem('CL-InfoPROX IUT de Chalons')} machines").openPopup();
                    var polygon4 = L.polygon([
                            [48.960585, 4.356951],
                            [48.960536, 4.357552],
                            [48.959747, 4.357316],

                            [48.959134, 4.356930],
                            [48.959338, 4.356372],
                            [48.959857, 4.356647]
                        ]).addTo(mymap);
                    
                    var iutT = L.marker([48.26849805401285, 4.079836753203654], {icon: customIconSchool}).addTo(mymap);
                    iutT.bindPopup("<div>IUT Troyes <div>Un total de {$bds->getAllSystem('CL-InfoPROX IUT de Troyes')} machines").openPopup();
                    var polygon5 = L.polygon([
                            [48.269360, 4.079551],
                            [48.268347, 4.081889],
                            [48.265923, 4.078444],
                            
                            [48.267096, 4.076287]
                        ]).addTo(mymap);
                    
                    
                    var cR = L.marker([49.236399, 4.001663], {icon: customIconSchool}).addTo(mymap);
                    cR.bindPopup("<div>Campus Croix-Rouge <div>Un total de {$bds->getAllSystem('CL-InfoPROX Croix-Rouge')} machines").openPopup();
                    var polygon6 = L.polygon([
                            [49.237667, 4.003520],
                            [49.238358, 4.002594],
                            [49.238033, 4.001630],
                            
                            [49.238602, 4.000782],
                            [49.237845, 3.999405],
                            [49.237048, 3.998931],
                            [49.234650, 4.001863],

                            [49.235443, 4.003427],
                            [49.236098, 4.002742],
                            [49.236946, 4.004407]
                        ]).addTo(mymap);

                    var inspe = L.marker([49.232587, 4.068067], {icon: customIconSchool}).addTo(mymap);
                    inspe.bindPopup("<div>Campus INSPE Siege  <div>Un total de {$bds->getAllSystem('CL-InfoPROX INESPE')} machines").openPopup();
                    var polygon7 = L.polygon([
                            [49.232950, 4.067541],
                            [49.232470, 4.069803],
                            [49.232647, 4.070929],
                            
                            [49.232215, 4.071716],
                            [49.231343, 4.069668],
                            [49.232096, 4.067432],

                            [49.232582, 4.067338]
                        ]).addTo(mymap);

                     var esi = L.marker([49.23800876188602, 4.0645797554894845], {icon: customIconSchool}).addTo(mymap);
                     esi.bindPopup("<div>Campus ESI Siege  <div>Un total de {$bds->getAllSystem('CL-InfoPROX ESI')} machines").openPopup();    
                     var polygon8 = L.polygon([
                            [49.238281, 4.064014],
                            [49.237826, 4.065429],
                            [49.237124, 4.064798],
                            
                            [49.237630, 4.063327]
                        ]).addTo(mymap);
                        
                    var cCC = L.marker([48.299485, 4.079408], {icon: customIconSchool}).addTo(mymap);
                    cCC.bindPopup("<div> Campus des Comptes de Champagne <div>Un total de {$bds->getAllSystem('CL-InfoPROX Campus Compte de Champagne')} machines").openPopup();
                    var polygon9 = L.polygon([
                            [48.299730, 4.079386],
                            [48.299314, 4.079819],
                            [48.299157, 4.079437],
                            
                            [48.299515, 4.078940]
                        ]).addTo(mymap);
                    
                    var cS = L.marker([49.22830066970603, 4.015840567572561], {icon: customIconSchool}).addTo(mymap);
                    cS.bindPopup("<div> UFR de Médecine et de Pharmacie <div>Un total de {$bds->getAllSystem('CL-InfoPROX Santé')} machines").openPopup();
                    var polygon9 = L.polygon([
                            [49.229010, 4.016076],
                            [49.227729, 4.017231],
                            [49.226577, 4.016156],
                            
                            [49.227501, 4.014589],
                            [49.228501, 4.014544]
                        ]).addTo(mymap);

                /////////// Bibliothèque Universitaire ///////////
                    var customIconBu = L.icon({
                        iconUrl: 'Templatic-map-icons/libraries.png',
                        iconSize :[25,40],
                        iconAnchor : [15,40],
                        popupAnchor: [-2, -35]
                    }); 
                    
                    var buCR = L.marker([49.235610, 4.001168], {icon: customIconBu}).addTo(mymap);
                    buCR.bindPopup("<div>BU Croix-Rouge <div>Un total de {$bds->getAllSystem('CL-BU Croix-Rouge')} machines").openPopup();
                    var circle = L.circle([49.235610, 4.001168], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 45
                            }).addTo(mymap);

                    
                    var buCCC = L.marker([48.299336, 4.079655], {icon: customIconBu}).addTo(mymap);
                    buCCC.bindPopup("<div>BU Campus des Comptes de Champagne <div>Un total de {$bds->getAllSystem('CL-BU Campus Comtes de Champagne')} machines").openPopup();
                    var circle = L.circle([48.299336, 4.079655], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 10
                            }).addTo(mymap);

                    var buIUTT = L.marker([48.269284451746216, 4.079548420851766], {icon: customIconBu}).addTo(mymap);
                    buIUTT.bindPopup("<div>BU IUT Troyes <div>Un total de {$bds->getAllSystem('CL-BU IUT de Troyes')} machines").openPopup();
                    var circle = L.circle([48.269284451746216, 4.079548420851766], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 10
                            }).addTo(mymap);

                    var buMH = L.marker([49.243659, 4.061571], {icon: customIconBu}).addTo(mymap);
                    buMH.bindPopup("<div>BU Moulin de la Housse <div>Un total de {$bds->getAllSystem('CL-BU Moulin de la House')} machines").openPopup();
                    var circle = L.circle([49.243659, 4.061571], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 50
                            }).addTo(mymap);

                    var buINSPE = L.marker([49.232749, 4.067896], {icon: customIconBu}).addTo(mymap);
                    buINSPE.bindPopup("<div>BU INSPE <div>Un total de {$bds->getAllSystem('CL-BU INESPE')} machines").openPopup();
                    var circle = L.circle([49.232749, 4.067896], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 20
                            }).addTo(mymap);
                    var buS = L.marker([49.227741, 4.016632], {icon: customIconBu}).addTo(mymap);
                    buS.bindPopup("<div>BU Santé <div>Un total de {$bds->getAllSystem('CL-BU Santé')} machines").openPopup();     
                    var circle = L.circle([49.227741, 4.016632], {
                                color: 'red',
                                fillColor: '#f03',
                                fillOpacity: 0.5,
                                radius: 25
                            }).addTo(mymap); 

                /////////// Délimitation ///////////
                    polygon.bindPopup("Délimitation ");        
                    polygon2.bindPopup("Délimitation ");
                    polygon3.bindPopup("Délimitation ");
                    polygon4.bindPopup("Délimitation ");
                    polygon5.bindPopup("Délimitation ");
                    polygon6.bindPopup("Délimitation ");
                    polygon7.bindPopup("Délimitation ");
                    var legend = L.control({ position: "bottomleft" });
                    legend.onAdd = function(mymap) 
                    {
                        var div = L.DomUtil.create("div", "#mymap");
                        div.innerHTML += "<h4>Tegnforklaring</h4>";
                        div.innerHTML += '<i style="background: #477AC2"></i><span>Water</span><br>';
                        div.innerHTML += '<i style="background: #448D40"></i><span>Forest</span><br>';
                        div.innerHTML += '<i style="background: #E6E696"></i><span>Land</span><br>';
                        div.innerHTML += '<i style="background: #E8E6E0"></i><span>Residential</span><br>';
                        div.innerHTML += '<i style="background: #FFFFFF"></i><span>Ice</span><br>';
                        div.innerHTML += '<i class="icon" style="background-image: url(https://d30y9cdsu7xlg0.cloudfront.net/png/194515-200.png);background-repeat: no-repeat;"></i><span>Grænse</span><br>';
                            return div;
                    };
                    legend.addTo(map);
                }
        JS
        );
    }

/////////////////////       Pas connecter               /////////////////////
    else
    {
        header("Location: http://localhost:84/dashboard/etapeFinal/formSHA512.php");
    }
/////////////////////       Affichage du HTML           ////////////////////
    echo $p->toHTML();