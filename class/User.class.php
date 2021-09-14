<?php

require_once "autoload.inc.php";

class User
{
    /**
     * Prénom de l'utilisateur.
     * @var string $lastName
     */
    private $lastName;
    /**
     * Nom de l'utilisateur.
     * @var string $firstName
     */
    private $firstName;
    /**
     * Login de l'utilisateur.
     * @var string $login
     */
    private $login;
    /**
     * Donnée de connection
     * @var bool $connected
     */
    private $connected;

    const session_key = "__user__"  ;


    private function __construct()
    {
    }

    /**
     * Accès à firstName
     * @return string
     */
    public function firstName(): string
    {
        return $this->firstName;
    }

    /**
     * Produit un affichage du nom prénom et login 
     * @return string
     */
    public function profile() : string
    {
        $html=<<<HTML
        <div>
            <div>
              Prénom : {$this->firstName}
            </div>
            <div>
              Nom : {$this->lastName}
            </div>
                    
            <div>
              Login : {$this->login}
            </div>
             
        </div>

        HTML;
        return $html;
    }
    /**
     * Produit un formulaire de déconnexion.
     * @param string $action
     * @param string $text
     * @return string
     */
    public static function logoutForm(string $action, string $text) : string
    {
        $html = <<<HTML
        <div>
            <form action="$action" method="GET">
                    <div>
                        <input name="logout" hidden>
                        <input class="bouton" type="submit" value="$text">
                    </div>
            </form>
        </div>
        HTML;
        return $html;
    }
    /**
     * Produit un formulaire de connexion sécurisé
     * @param string $action
     * @param $submitText 
     * @return string 
     */
    public static function  loginFormSHA512(string $action, string $submitText = "OK")
    {
        Session::start();
        $challenge = self::randomString(7);
        if (isset($_SESSION[User::session_key]))
            $_SESSION[self::session_key]['challenge'] = $challenge;
        else{
            $_SESSION[self::session_key] = ['challenge' => $challenge];
        }
        /** @var string $challenge chaîne de caractère unique à l'utilisaateur lors de la connexion */
        //L'affichage du formulaire
            $html = <<<HTML
            
            <form name="login" action="$action" method="GET">
                <div >
                    <ul>
                        <label >
                                Entrer votre identifiant 
                                <input type="text" name="login" required>
                        </label>
                </ul>
                </div>
                <div >
                    <ul>
                        <label >
                            Entrer votre mot de passe 
                            <input type="password" name="pass" required>
                        </label>
                    </ul>
                </div>
                <input name="code" hidden>
                <div>
                <input type="submit" value="$submitText">
                </div>
            </form>
            <script src="js/sha512.js"></script>
            <!-- encode le login et le mot de passe pour qu'ils ne soient pas lisible -->
            <script>
                const loginForm = document.forms['login'];
                loginForm.onsubmit = function () 
                {
                    const password = loginForm.elements['pass'].value;
                    const login = loginForm.elements['login'].value;
                    const sha_login = CryptoJS.SHA512(login);
                    const sha_password = CryptoJS.SHA512(password);
                    loginForm.elements['code'].value = CryptoJS.SHA512( sha_login + sha_password + '{$challenge}');
                    loginForm.elements['pass'].value = "";
                    loginForm.elements['login'].value = "";
                }
            </script>
            HTML;
        return $html;
    }
    /**
     * Cherche un enregistrement dans la table User où le mot de passe/ login sont valide de façon sécurisée 
     * @param array $data
     * @return User
     * @throws AuthentificationException
     * @throws SessionException
     * @throws Exception
     */
    public static function createFromAuth512(array $data) : User
    {
        $code = "";
        $challenge = "";
        Session::start();
        // Vérifie si l'état de la session
        if (isset($_SESSION[User::session_key]) && isset($data['code']))
        {
            $challenge = $_SESSION[self::session_key]['challenge'];
            $code = $data['code'];
        }
        else{
            throw new SessionException("Il y a un problème de session");
        }

        $stmt=MyPDO::getInstance()->prepare(<<<SQL
        SELECT id, lastName, firstName, login
        FROM user
        WHERE SHA2( CONCAT( SHA2(login, 512),
                            sha512pass,
                            '$challenge'
                    ), 512 ) = :mycode
        SQL
        );
        $stmt->execute([':mycode'=>$code]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "User");

        if( ($obj = $stmt->fetch()) !== false)
        {
            if( isset($_SESSION[User::session_key]))
                $_SESSION[User::session_key]["connected"] = true;
            else
            {
                $_SESSION[User::session_key] = ["connected" => true];
            }
            return $obj;
        }
        else
            throw new AuthentificationException("L'identifiant ou le mot de passe est invalide");
    }

    /**
     * Retourne l'état de la donnée de session 
     * @return bool
     * @throws SessionException
     */
    public static function isConnected() : bool
    {
        $status = false;
        Session::start();
        if(isset($_SESSION[User::session_key]) && isset($_SESSION[User::session_key]['connected']))
            $status = $_SESSION[User::session_key]["connected"];

        return $status;
    }

    /**
     * Détruit l'ensemble des données de session associées à l'utilisateur si une valeur logout de formulaire est reçue
     * @throws SessionException
     */
    public static function logoutIfRequested() : void
    {
        Session::start();
        if (self::isConnected() && isset($_REQUEST['logout']))
        {
            unset($_SESSION[User::session_key]);
        }
    }

    /**
     * Enregistre l'objet User dans la session.
     * @throws SessionException
     */
    public function saveIntoSession() : void
    {
        Session::start();
        if(isset($_SESSION[User::session_key]))
        {
            $_SESSION[User::session_key]['user'] = $this;
        }else
        {
            $_SESSION[User::session_key] = ['user' => $this];
        }
    }

    /**
     * Creation d'une session 
     * @return User
     * @throws NotInSessionException
     */
    public static function createFromSession() : User
    {
        if(isset($_SESSION[self::session_key]['user']) && ($_SESSION[self::session_key]['user'] instanceof User))
        {
            $ret = $_SESSION[self::session_key]['user'];
        }else{
            throw new NotInSessionException("L'utilisateur n'est pas dans la session");
        }
        return $ret;
    }

    /**
     * Produit un code aléatoire de longueur $size composé à partir des caractères [a-z], [A-Z] et [1-9].
     * @param int $size
     * @return string
     */
    public static function randomString(int $size) : string
    {
        $alpha = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $alpha .= strtolower($alpha) . "0123456789";
        $code = "";
        for($i=0;$i<$size;$i+=1)
        {
            $rand = rand(0,strlen($alpha)-1);
            $code.= $alpha[$rand];
        }
        return $code;
    }

}