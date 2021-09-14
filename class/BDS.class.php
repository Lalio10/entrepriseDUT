<?php
require "Config.class.php";
require_once "autoload.inc.php";

$conn = new PDO();   
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
class BDS {
    public function __construct(){}
    
    ////Page Graphique /////
        /**
         * La fonction renvoie tous les noms sous forme de tableau 
         */
        public function getAllNameMember()
        {
            global $conn;
            $sqlp="SELECT  Name  FROM [CM_RS1].[dbo].[v_Collection]  ";
            
            $stock="";
            
            $stmt = $conn->prepare($sqlp );
            if( $stmt->execute() === false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute();
            while ($row = $stmt->fetch( PDO::FETCH_ASSOC ))
            {
                $res="<tr><td>{$row['Name']}</td></tr>";
                $stock.=$res;
            }
            return $stock;
        }
        /**
         * La fonction renvoie tous les nombres sous forme de tableau 
         */
        public function getAllMemberCount()
        {
            global $conn;
            $sqlp="SELECT  Name, MemberCount  FROM [CM_RS1].[dbo].[v_Collection]";
            
            $stock="";
            
            $stmt = $conn->prepare($sqlp);
            if( $stmt->execute() === false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute();
            while ($row = $stmt->fetch( PDO::FETCH_ASSOC ))
            
            {
                $res=$row['MemberCount'];
                $stock.=$res;
            
            }
            return $stock;
        }
        /**
         * La fonction affiche sous forme de tableau le nombre poste en fonction des différentes catégories. 
         */
        public function getAllNameMemberCount():string
        {
            global $conn;
            $sqlp="SELECT Name, MemberCount  FROM [CM_RS1].[dbo].[v_Collection] order by  MemberCount DESC ";
            
            $stock="";
            
            $stmt = $conn->prepare($sqlp );
            if( $stmt->execute( ) === false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute();
            while ($row = $stmt->fetch( PDO::FETCH_ASSOC ))
            {
                
                $res="<tr><td>{$row['Name']}</td> <td>{$row['MemberCount']}</td></tr>";
                
                $stock.=$res;
            }
            return $stock;
        }
        /**
         * Donne le nombre de personne active 
         * @param $data 
         */
        
        public function getUserAct( $data)
        {
            global $conn;
            $sqlp="SELECT  ResourceID  from v_r_system WHERE Client0 = ?";
            
            $stock=0;
            $stmt = $conn->prepare($sqlp );
            if( $stmt->execute(array($data) )=== false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute(array($data));
            while ( $stmt->fetch( PDO::FETCH_ASSOC))
            {
                $stock++;    
            }
            return $stock;
        }
        /**
         * Donne le nombre de personne inactive
         */
        public function getUserActN($data)
        {
            global $conn;
            $sqlp="SELECT  ResourceID  from v_r_system WHERE Client0 is Null or Client0= ?";
            $stock=0;
            $stmt = $conn->prepare($sqlp );
            if( $stmt->execute(array($data))=== false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute(array($data));
            while ($stmt->fetch( PDO::FETCH_ASSOC ))
            {
                $stock++;
            }
            return $stock;
        }
        
        /**
         * Donne le nombre d'appareils allumé ou éteint
         * @param $data1 détermine si éteint ou allumé
         */
        
        public function getMachinOnOff($data)
        {
            global $conn;
            $sqlp="SELECT  ResourceID
            FROM v_CH_ClientSummary
            where ClientActiveStatus = ?";
            $stock=0;
            
            $stmt = $conn->prepare($sqlp);
            if( $stmt->execute(array($data)) === false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute(array($data));
            while ($stmt->fetch( PDO::FETCH_ASSOC ))
            {
            $stock++;
            }
            return $stock;
            
        }
        public function getAllWorktation():string
        {
            global $conn;
                $sqlp="SELECT ResourceID
                from [CM_RS1].[dbo].v_R_System 
                where Operating_System_Name_and0 like '%Workstation%' ";
                
                $stock="";
                
                $stmt = $conn->prepare($sqlp );
                if( $stmt->execute() === false ) 
                {
                    die( "Error connecting to SQL Server" );        
                }
                $stmt->execute();
                while ($stmt->fetch( PDO::FETCH_ASSOC ))
                {
                    $stock++;
                }
                return $stock;
        }
        /**
         * Donne tous les postes de travail Microsoft
         */
        public function getAllMicrosoft():string
        {
            global $conn;
                $sqlp="SELECT ResourceID
                from [CM_RS1].[dbo].v_R_System 
                where Operating_System_Name_and0 like '%Windows%' ";
                
                $stock="";
                
                $stmt = $conn->prepare($sqlp );
                if( $stmt->execute() === false ) 
                {
                    die( "Error connecting to SQL Server" );        
                }
                $stmt->execute();
                while ($stmt->fetch( PDO::FETCH_ASSOC ))
                {
                    $stock++;
                }
                return $stock;
        }
        /**
         * donne tous les autres postes de travail 
         */
        public function getAllOthers():string
        {
            global $conn;
                $sqlp="SELECT ResourceID
                from [CM_RS1].[dbo].v_R_System 
                where Operating_System_Name_and0 not like '%Windows%' and Operating_System_Name_and0 not like '%Workstation%' ";
                
                $stock="";
                
                $stmt = $conn->prepare($sqlp );
                if( $stmt->execute() === false ) 
                {
                    die( "Error connecting to SQL Server" );        
                }
                $stmt->execute();
                while ($stmt->fetch( PDO::FETCH_ASSOC ))
                {
                    $stock++;
                }
                return $stock;
        }
        /**
         * Donne un tableau de chassis avec noms identifiant unique et le domaine
         * @param $data (int) : type de chassis 
         */
        
        public function getAllChassis($data):string
        {
            global $conn;
            $sqlp="SELECT   v_R_System.ResourceID,
                            v_R_System.ResourceType,
                            v_R_System.Name0, 
                            v_R_System.SMS_Unique_Identifier0, 
                            v_R_System.Resource_Domain_OR_Workgr0, 
                            v_R_System.Client0 
                    FROM v_R_System inner join v_GS_SYSTEM_ENCLOSURE on v_GS_SYSTEM_ENCLOSURE.ResourceID = v_R_System.ResourceID 
                    WHERE v_GS_SYSTEM_ENCLOSURE.ChassisTypes0 = ?";
                
                $stock="";
                
                $stmt = $conn->prepare($sqlp );
                if( $stmt->execute(array($data) ) === false ) 
                {
                    die( "Error connecting to SQL Server" );        
                }
                $stmt->execute(array($data));
                while ($row = $stmt->fetch( PDO::FETCH_ASSOC ))
                {
                    $res="<tr><td>{$row['Name0']}</td> <td>{$row['SMS_Unique_Identifier0']}</td> <td>{$row['Resource_Domain_OR_Workgr0']}</td></tr>";
                    $stock.=$res;     
                }
                return $stock;
        }
        /**
         * Donne le nombre de poste qui ont un certain type de chassis 
         * $data (int) : type de chassis 
         */
        
        public function getAllChassisWithNumber( $data):int
        {
            global $conn;
            $sqlp="SELECT  *
                    FROM v_R_System inner join v_GS_SYSTEM_ENCLOSURE on v_GS_SYSTEM_ENCLOSURE.ResourceID = v_R_System.ResourceID 
                    WHERE v_GS_SYSTEM_ENCLOSURE.ChassisTypes0 = ?";
                
            $stock=0;
                
            $stmt = $conn->prepare($sqlp );
            if( $stmt->execute(array($data) ) === false ) 
            {
                die( "Error connecting to SQL Server" );        
            }
            $stmt->execute(array($data));
            while ( $stmt->fetch( PDO::FETCH_ASSOC ))
            {      
                $stock++; 
            }
            return $stock;
        }
    ////Page Tableau 1/2////
    /**
     * Donne le nom et le nombre de poste qui ont un certain status 
     * @param $data : 
     */
    public function getPointOfDeployementsStatus($data)
    {
        global $conn;
        $sqlp="SELECT vSMS_SC_SysResUse.ServerName,   v_PackageStatusDistPointsSumm.InstallStatus, COUNT(*) as TotalCounts,
        CASE v_PackageStatusDistPointsSumm.InstallStatus  
        WHEN 'Content monitoring' THEN 'Failed'  
        WHEN 'Package installation failed' THEN 'Failed'  
        WHEN 'Retrying package installation' THEN 'Failed'  
        WHEN 'Content updating' THEN 'In Progress'  
        WHEN 'Waiting to install package' THEN 'In Progress'  
        WHEN 'Package Installation complete' THEN 'Success' 
        END  AS 'Status'
        FROM [CM_RS1].[dbo].vSMS_SC_SysResUse  INNER JOIN [CM_RS1].[dbo]. v_PackageStatusDistPointsSumm ON v_PackageStatusDistPointsSumm.ServerNALPath = vSMS_SC_SysResUse.NALPath  LEFT JOIN [CM_RS1].[dbo].v_PackageStatusRootSummarizer ON v_PackageStatusRootSummarizer.PackageID = v_PackageStatusDistPointsSumm.PackageID  
        WHERE vSMS_SC_SysResUse.RoleName = ? 
        GROUP BY vSMS_SC_SysResUse.ServerName, v_PackageStatusDistPointsSumm.InstallStatus";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute(array($data)) === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute(array($data));
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="<tr><td>{$row['ServerName']}</td> <td>{$row['InstallStatus']}</td><td>{$row['TotalCounts']}</td><td>{$row['Status']}</td></tr> ";
            $stock.=$res;
            
        }
        return $stock;
    }
    /**
     * Donne la catégorie en fonction d'une certaine durée d'une demande d'installation d'une application
     */
    public function getPendingApplicationInstallRequest()
    {
        global $conn;
        $sqlp=" SELECT  'Install Attempts' AS [Category], 'Last 24 Hours' as [Duration],
                    SUM(CASE WHEN a.Source = 0 AND c.Client_Type0 = 1 THEN 1 END) AS [CatalogInstalls],
                    SUM(CASE WHEN a.Source = 1 THEN 1 END) + SUM(CASE WHEN a.Source = 0 AND c.Client_Type0 = 3 THEN 1 END) AS [PortalInstalls]   
                FROM  [CM_RS1].[dbo].UserAppModelSoftwareRequest a   JOIN [CM_RS1].[dbo].UserMachineRelation b on a.RelationshipResourceID = b.RelationshipResourceID   JOIN [CM_RS1].[dbo].v_R_System c on b.MachineResourceID = c.ResourceID 
                where DateDiff(Day, a.creationtime,GETDATE()) < 1 
        Union 
                SELECT  'Install Attempts' AS [Category], 'Last 7 Days' as [Duration], 
                    SUM(CASE WHEN a.Source = 0 AND c.Client_Type0 = 1 THEN 1 END) AS [CatalogInstalls],
                    SUM(CASE WHEN a.Source = 1 THEN 1 END) + SUM(CASE WHEN a.Source = 0 AND c.Client_Type0 = 3 THEN 1 END) AS [PortalInstalls] 
                FROM [CM_RS1].[dbo].UserAppModelSoftwareRequest a   JOIN [CM_RS1].[dbo].UserMachineRelation b on a.RelationshipResourceID = b.RelationshipResourceID   JOIN [CM_RS1].[dbo].v_R_System c on b.MachineResourceID = c.ResourceID 
                where DateDiff(Day, a.creationtime,GETDATE()) < 7 Union SELECT  'Install Attempts' AS [Category], 'Last 30 Days' as [Duration],
                    SUM(CASE WHEN a.Source = 0 AND c.Client_Type0 = 1 THEN 1 END) AS [CatalogInstalls],
                    SUM(CASE WHEN a.Source = 1 THEN 1 END) + SUM(CASE WHEN a.Source = 0 AND c.Client_Type0 = 3 THEN 1 END) AS [PortalInstalls] 
                FROM [CM_RS1].[dbo].UserAppModelSoftwareRequest a   JOIN [CM_RS1].[dbo].UserMachineRelation b on a.RelationshipResourceID = b.RelationshipResourceID   JOIN [CM_RS1].[dbo].v_R_System c on b.MachineResourceID = c.ResourceID 
                where DateDiff(Day, a.creationtime,GETDATE()) < 30";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="<tr><td>{$row['Category']}</td> <td>{$row['Duration']}</td><td>{$row['CatalogInstalls']}</td><td>{$row['PortalInstalls']}</td></tr> ";
            $stock.=$res;
            
        }
        return $stock;
    }
    /**
     * Donne le status de chaque client 
     */
    public function getClientStatus()
    {
        global $conn;
        
        $sqlp="SELECT clientstate, ClientStateDescription, count(*) as ClientTotal 
        
        FROM [CM_RS1].[dbo].v_R_System SYS
        
        LEFT JOIN [CM_RS1].[dbo].v_CH_ClientSummary CHS ON SYS.ResourceID = CHS.ResourceID
        
        WHERE SYS.Obsolete0 = 0 AND CHS.ResourceID is not null
        
        Group by ClientStateDescription, clientstate";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="<tr><td>{$row['clientstate']}</td> <td>{$row['ClientStateDescription']}</td><td>{$row['ClientTotal']}</td></tr> ";//<td>{$row['Resource_Domain_OR_Workgr0']}</td></tr>
            $stock.=$res;
            
        }
        return $stock;
    
    }
    /**
     * Donne les informations de l'ordinateur comme son nom, site et mise à jour 
     */
    public function getDELLMachinInfosComputer()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT  VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
            
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td> 
            <td>{$row['Computer Name']}</td> 
            <td>{$row['CaseType']}</td>
            <td>{$row['AssignedSite']}</td>
            <td>{$row['ADSiteName']}</td>
            
            <td>{$row['LastBootDate']}</td>
            <td>{$row['LastBootDateAge']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne son numéro de série et son tag
     */
    public function getDELLMachinSerialNumber()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            if(isset($row)){
                $res="<tr>
                <td>Ordi {$cpt}</td> 
                <td>{$row['SerialNumber']}</td>
                <td>{$row['AssetTag']}</td>
                <td>{$row['ReleaseDate']}</td>
                </tr> ";
                $stock.=$res;
            }
        }
        return $stock;
    }
    /**
     * Donne les informations liés au processeur 
     */
    public function getDELLMachinProcessor()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td> 
            <td>{$row['ProcessorName']}</td>
            <td>{$row['Manufacturer']}</td>
            <td>{$row['Model']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne les informations liés au Système d'Exploitation
     */
    public function getDELLMachinOs()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td>
            <td>{$row['OSName']}</td>
            <td>{$row['OSType']}</td>
            <td>{$row['OSInstallDate']}</td>
            <td>{$row['OSInstallDateAge']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne les informations liés au client comme le nom de domaine et d'utilisateur  
     */
    public function getDELLMachinInfosClientDomainName()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td> 
            <td>{$row['DomainName']}</td>
            <td>{$row['UserName']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne les informations liés au client comme l'email et le rôle   
     */
    public function getDELLMachinInfosClientEmail()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td> 
            <td>{$row['EMailID']}</td>
            <td>{$row['Role']}</td>
            <td>{$row['ServicePack']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne les informations liés au client comme la version du client et sa date de création 
     */
    public function getDELLMachinInfosClientVersion()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td> 
            <td>{$row['Version']}</td>
            <td>{$row['ClientVersion']}</td>
            <td>{$row['ClientCreationDate']}</td>
            <td>{$row['ClientCreationDateAge']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne les informations liés à l'ordinateur comme l'espace de stockage 
     */
    public function getDELLMachinMemory()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            $res="<tr>
            <td>Ordi {$cpt}</td> 
            <td>{$row['TotalPhysicalMemory0']}</td>
            <td>{$row['TotalRAMSize(GB)']}</td>
            <td>{$row['TotalHDDSize(GB)']}</td>
            <td>{$row['LastHWScan']}</td>
            <td>{$row['LastHWScanAge']}</td>
            </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * Donne les informations liés au client comme le BIOS
     */
    public function getDELLMachinBios()
    {
        global $conn;
        $cpt=0;
        $sqlp="SELECT VRS.Netbios_Name0 as 'Computer Name',
    
            Case    when SE.ChassisTypes0 = 1 Then 'VMWare'                                 when SE.ChassisTypes0 IN('3','4')Then 'Desktop'
                    when SE.ChassisTypes0 IN('8','9','10','11','12','14') Then 'Laptop'     when SE.ChassisTypes0 = 6 Then 'Mini Tower'
                    when SE.ChassisTypes0 = 7 Then 'Tower'                                  when SE.ChassisTypes0 = 13 Then 'All in One'
                    when SE.ChassisTypes0 = 15 Then 'Space-Saving'                          when SE.ChassisTypes0 = 17 Then 'Main System Chassis'
                    when SE.ChassisTypes0 = 21 Then 'Peripheral Chassis'                    when SE.ChassisTypes0 = 22 Then 'Storage Chassis'
                    when SE.ChassisTypes0 = 23 Then 'Rack Mount Chassis'                    when SE.ChassisTypes0 = 24 Then 'Sealed-Case PC'
        
                Else 'Others'
        
                End 'CaseType',
            
                LEFT(MAX(NAC.IPAddress0),
            
                ISNULL(NULLIF(CHARINDEX(',',MAX(NAC.IPAddress0)) - 1, -1),LEN(MAX(NAC.IPAddress0))))as 'IPAddress',
            
                MAX (NAC.MACAddress0) as 'MACAddress',
            
                SAS.SMS_Assigned_Sites0 as 'AssignedSite',
            
                VRS.Client_Version0 as 'ClientVersion',
            
                VRS.Creation_Date0 as 'ClientCreationDate',
            
                DateDiff(D, VRS.Creation_Date0, GetDate()) 'ClientCreationDateAge',
            
                VRS.AD_Site_Name0 as 'ADSiteName',
            
                OS.InstallDate0 AS 'OSInstallDate',
            
                DateDiff(D, OS.InstallDate0, GetDate()) 'OSInstallDateAge',
            
                Convert(VarChar, OS.LastBootUpTime0,10) as 'LastBootDate',
            
                DateDiff(D, Convert(VarChar, OS.LastBootUpTime0,10), GetDate()) as 'LastBootDateAge',
            
                BIOS.SerialNumber0 as 'SerialNumber',
            
                SE.SMBIOSAssetTag0 as 'AssetTag',
            
                BIOS.ReleaseDate0 as 'ReleaseDate',
            
                BIOS.Name0 as 'BiosName',
            
                BIOS.SMBIOSBIOSVersion0 as 'BiosVersion',
            
                PRO.Name0 as 'ProcessorName',
        
            Case    when CS.Manufacturer0 like 'VMware%' Then 'VMWare'                      when CS.Manufacturer0 like 'Gigabyte%' Then 'Gigabyte'
                    when CS.Manufacturer0 like 'VIA Technologies%' Then 'VIA Technologies'  when CS.Manufacturer0 like 'MICRO-STAR%' Then 'MICRO-STAR'
        
                Else CS.Manufacturer0 End 'Manufacturer',
        
            CS.Model0 as 'Model',
        
            CS.SystemType0 as 'OSType',
        
            CS.Domain0 as 'DomainName',
        
            VRS.User_Domain0+'\'+ VRS.User_Name0 as 'UserName',
        
            U.Mail0 as 'EMailID',
        
            Case    when CS.domainrole0 = 0 then 'Standalone Workstation'                   when CS.domainrole0 = 1 Then 'Member Workstation'
                    when CS.domainrole0 = 2 Then 'Standalone Server'                        when CS.domainrole0 = 3 Then 'Member Server'
                    when CS.domainrole0 = 4 Then 'Backup Domain Controller'                 when CS.domainrole0 = 5 Then 'Primary Domain Controller'
        
            End 'Role',
        
            Case    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Enterprise Edition' Then 'Microsoft(R) Windows(R) Server 203 Enterprise Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Standard Edition' Then 'Microsoft(R) Windows(R) Server 203 Standard Edition'
                    when OS.Caption0 = 'Microsoft(R) Windows(R) Server 203, Web Edition' Then 'Microsoft(R) Windows(R) Server 203 Web Edition'

                Else OS.Caption0
        
            End 'OSName',
        
            OS.CSDVersion0 as 'ServicePack',
        
            OS.Version0 as 'Version',
        
            RAM.TotalPhysicalMemory0 as TotalPhysicalMemory0,
        
            ((RAM.TotalPhysicalMemory0/1024)/1024) as 'TotalRAMSize(GB)',
        
            max(LD.Size0 / 1024) AS 'TotalHDDSize(GB)',
        
            WS.LastHWScan as 'LastHWScan',
        
            DateDiff(D, WS.LastHwScan, GetDate()) as 'LastHWScanAge'
        
            from
        
            [CM_RS1].[dbo].v_R_System_Valid VRS
        
            Left Outer join [CM_RS1].[dbo].v_GS_PC_BIOS BIOS on BIOS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_OPERATING_SYSTEM OS on OS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_WORKSTATION_STATUS WS on WS.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_COMPUTER_SYSTEM CS on CS.ResourceId = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_X86_PC_MEMORY RAM on RAM.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_PROCESSOR PRO on PRO.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_GS_SYSTEM_ENCLOSURE SE on SE.ResourceID = VRS.ResourceId
        
            Left Outer join [CM_RS1].[dbo].v_RA_System_SMSAssignedSites SAS on SAS.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_FullCollectionMembership FCM on FCM.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_NETWORK_ADAPTER_CONFIGURATION NAC on NAC.ResourceID = VRS.ResourceId
        
            left outer join [CM_RS1].[dbo].v_GS_LOGICAL_DISK LD on LD.ResourceID = Vrs.ResourceId AND LD.DriveType0 = 3
        
            Left Outer join [CM_RS1].[dbo].v_R_User U on VRS.User_Name0 = U.User_Name0
        
            GROUP BY    VRS.Netbios_Name0,                      SE.ChassisTypes0,   SAS.SMS_Assigned_Sites0,    VRS.Client_Version0,Vrs.Creation_Date0,
                        Vrs.AD_Site_Name0,                      OS.InstallDate0,    OS.LastBootUpTime0,         BIOS.SerialNumber0,
                        SE.SMBIOSAssetTag0,                     BIOS.ReleaseDate0,  BIOS.Name0,                 BIOS.SMBIOSBIOSVersion0,
                        PRO.Name0,                              CS.Manufacturer0,   CS.Model0,                  CS.SystemType0,
                        CS.Domain0,                             Vrs.User_Domain0,   Vrs.User_Name0,             U.Mail0,
                        CS.DomainRole0,                         OS.Caption0,        OS.CSDVersion0,             OS.Version0,
                        RAM.TotalPhysicalMemory0,               WS.LastHWScan
            
            order by VRS.Netbios_Name0";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $cpt++;
            if(isset($row)){
                $res="<tr>
                <td>Ordi {$cpt}</td> 
                <td>{$row['BiosName']}</td>
                <td>{$row['BiosVersion']}</td>
                </tr> ";
                $stock.=$res;
            }
        }
        return $stock;
    }
    public function getPackageApplyofNumber()
    {
        global $conn;
        $sqlp="SELECT 'Application' as [DeploymentType], 
                CASE     
                    WHEN coll.collectiontype = 1 THEN 'User'     
                    WHEN Coll.collectiontype = 2 THEN 'Device'    
                END AS [TargetType],    COUNT(*) as [Count]        
            From v_DeploymentSummary DS    join v_collection coll on coll.collectionid = ds.collectionid    
            WHERE ProgramName is null    
            group by coll.collectiontype        
        union    
            select 'Pakcage'as [DeploymentType],    
                CASE       
                    WHEN coll.collectiontype = 1 THEN 'User'       
                    WHEN Coll.collectiontype = 2 THEN 'Device'    
                END AS [TargetType], 
                COUNT(*) as 'Count'    
            From v_DeploymentSummary DS    join v_collection coll on coll.collectionid = ds.collectionid    
            WHERE not(ProgramName is null) and not(ProgramName = '*')    
            group by coll.collectiontype    
        union    
            select 'TaskSequence'as [DeploymentType],    
                CASE      
                    WHEN coll.collectiontype = 1 THEN 'User'      
                    WHEN Coll.collectiontype = 2 THEN 'Device'      
                END AS [TargetType],    
                COUNT(*) as [Count]    
            From v_DeploymentSummary DS    join v_collection coll on coll.collectionid = ds.collectionid   
            WHERE ProgramName = '*'    
            group by coll.collectiontype ";
        $stock=[];
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="<tr><td>{$row['DeploymentType']}</td> <td>{$row['TargetType']}</td><td>{$row['Count']}</td></tr> ";//<td>{$row['Resource_Domain_OR_Workgr0']}</td></tr>
            $stock.=$res;
            
        }
        return $stock;


    }
    /**
     * Donne tous les postes de travail Workstation
     */
    
    
    /**
     * Donne le nom, la version ainsi que le répertoire où se trouve l'installation
     */
    public function getAcrobatAdobeMachin()
    {
        global $conn;

        $sqlp="SELECT [CM_RS1].[dbo].v_R_System.Name0 as 'Nom', 
        [CM_RS1].[dbo].[v_GS_INSTALLED_SOFTWARE].ProductName0 as 'Nom du produit', 
		[CM_RS1].[dbo].[v_GS_INSTALLED_SOFTWARE].ProductVersion0 as 'Version', 
        [CM_RS1].[dbo].[v_GS_INSTALLED_SOFTWARE].UninstallString0 as 'Installation', 
        [CM_RS1].[dbo].[v_GS_INSTALLED_SOFTWARE].InstalledLocation0 as 'Chemin'
        from  [CM_RS1].[dbo].v_R_System inner join [CM_RS1].[dbo].[v_GS_INSTALLED_SOFTWARE] on [CM_RS1].[dbo].[v_GS_INSTALLED_SOFTWARE].ResourceID = [CM_RS1].[dbo].v_R_System.ResourceID
        where ProductName0 like '%Adobe Acrobat %' ";
        
        $stock="";
        
        $stmt = $conn->prepare( $sqlp );
        if( $stmt->execute( ) === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ( $row = $stmt->fetch( PDO::FETCH_ASSOC ))
        {      
            $res="<tr><td>{$row['Nom']}</td> <td>{$row['Nom du produit']}</td> <td>{$row['Version']}</td><td>{$row['Installation']}</td><td>{$row['Chemin']}</td></tr>";
            $stock.=$res;
        }
        return $stock;
    }
    // Donne les composantes et leur nombre de machine  
    public function getComponent()
    {
        global $conn;
        $sqlp="SELECT [Name] 
        ,[MemberCount]
            FROM [CM_RS1].[dbo].[v_Collection]
            where Name like 'cl-%'  and Name not like '%dca%'
            order by MemberCount";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="  <tr><td>{$row['Name']}</td> 
                        <td>{$row['MemberCount']}</td>  
                    </tr> ";
            $stock.=$res;
        }
        return $stock;
    }
    /**
     * 
     */
    public function getStatesOfReplicationLinks()
    {
        global $conn;
        $sqlp="SELECT SiteCode, ParentSiteCode,  ChildSiteCode, ServerRole, Name, SQLInstance,  CASE lnk.OverallLinkStatus   

        WHEN 0 THEN 'Deleted'       WHEN 1 THEN 'Tombstoned'    WHEN 2 THEN 'Active'    
        WHEN 3 THEN 'Initializing'  WHEN 4 THEN 'NotStarted'    WHEN 5 THEN 'Error'    
        WHEN 6 THEN 'Unknown'       WHEN 7 THEN 'Degraded'      WHEN 8 THEN 'Failed'   
       
           END AS OverallLinkStatus  
       
          FROM [CM_RS1].dbo.RCM_ReplicationLinkSummary_Child lnk   INNER JOIN [CM_RS1].dbo.ServerData srv   ON lnk.ChildSiteCode = srv.SiteCode ";
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="  <tr><td>{$row['SiteCode']}</td> 
                        <td>{$row['ParentSiteCode']}</td>
                        <td>{$row['ChildSiteCode']}</td>
                        <td>{$row['ServerRole']}</td>
                        <td>{$row['Name']}</td>
                        <td>{$row['SQLInstance']}</td>
                        <td>{$row['OverallLinkStatus']}</td>
                    </tr> ";
            $stock.=$res;
            
        }
        return $stock;
    }
    /**
     * Donne le status de chaque site ainsi que leur progression 
    */
     public function getStatusSite()
    {
        global $conn;
        $sqlp="SELECT SiteCode, Status, Updated,   AvailabilityState,

        case when status = 1 then 'Warning'                 when Status = 2 then 'Error' else 'Success' 
        end StatusString,   
        case when AvailabilityState = 1 then 'Warning'      when AvailabilityState = 2 then 'Error' else 'Success' end StateString  
        
        from [CM_RS1].dbo.  vSummarizers_SiteStatus  ";
        
        $stock="";
        
        $stmt = $conn->prepare($sqlp);
        if( $stmt->execute() === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute();
        while ($row= $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="  <tr><td>{$row['SiteCode']}</td> 
                        <td>{$row['Status']}</td>
                        <td>{$row['Updated']}</td>
                        <td>{$row['AvailabilityState']}</td>
                        <td>{$row['StatusString']}</td>
                        <td>{$row['StateString']}</td>
                    </tr> ";
            $stock.=$res;
            
        }
        return $stock;
    }
    /*
    * Donne tous le nombre de machine en fonction de son nom
    * @* @param string  $data :  Le nom du système 
    */
    public function getAllSystem($data):string
    {
        global $conn;
        $sqlp="SELECT  [MemberCount]
        FROM [CM_RS1].[dbo].[v_Collection]
        where Name =? ";
        
        $stock="";
        
        $stmt = $conn->prepare($sqlp );
        if( $stmt->execute(array($data) ) === false ) 
        {
            die( "Error connecting to SQL Server" );        
        }
        $stmt->execute(array($data));
        while ($row = $stmt->fetch( PDO::FETCH_ASSOC ))
        {
            $res="{$row['MemberCount']}";
            
            $stock.=$res;
        }
        return $stock;
    }
}