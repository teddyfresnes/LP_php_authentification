<?php
class UserController {
    private $userManager;
    private $user;

    public function __construct($db1) {
        require('./Model/User.php');
        require_once('./Model/UserManager.php');
        $this->userManager = new UserManager($db1);
        $this->db = $db1;
    }

    public function login() {
        require('./View/login.php');
    }

    public function doLogin() {
        //$this->user = new User();

        // Vérifier si les variables POST existent
        if (isset($_POST['email']) && isset($_POST['password'])) {
            // Récupérer les valeurs du formulaire
            $email = $_POST['email'];
            $password = $_POST['password'];
            //var_dump($_POST);

            // vérifier l'existence de l'utilisateur
            $result = $this->userManager->login($email, $password);

            if ($result) {
                $info = "Connexion réussie";
                $_SESSION['user'] = $result;
                require('./View/home.php');
            } else {
                $info = "Identifiants incorrects.";
                require('./View/login.php');
            }
        } else {
            $info = "Veuillez fournir l'email et le mot de passe.";
            require('./View/login.php');
        }
        echo $info;

        //require('./View/home.php');
    }


    public function create() {
        if (
            isset($_POST['email']) &&
            isset($_POST['password']) &&
            isset($_POST['lastName']) &&
            isset($_POST['firstName']) &&
            isset($_POST['address']) &&
            isset($_POST['postalCode']) &&
            isset($_POST['city']) &&
            isset($_POST['country'])
        )
        {
            $alreadyExist = $this->userManager->findByEmail($_POST['email']);
            if (!$alreadyExist) {
                $newUser = new User($_POST);
                $this->userManager->create($newUser);
                require('./View/login.php');
                exit;
            } else {
                $error = "ERROR: This email (".$_POST['email'].") is used by another user";
                echo $error;
            }
        }
        require('./View/createAccount.php');
    }

    public function display() {
        require('./View/home.php');
    }


    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }


    // petit bonus bouton pour changer son statut admin
    public function becomeAdmin()
    {
        var_dump($_SESSION['user']);
        echo "Reached becomeAdmin method";
        if (isset($_SESSION['user'])) {
            $email = $_SESSION['user']['email'];
            echo "Email: ".$email;
            $success = $this->userManager->becomeAdmin($email);

            if ($success) {
                $_SESSION['user']['admin'] = 1;
                header("Location: index.php?message=nowadmin");
                exit();
            } else {
                header("Location: index.php?message=notadmin");
                exit();
            }
        } else {
            header("Location: index.php");
            exit();
        }
    }

    public function showUsers()
    {
        // check if admin
        if (isset($_SESSION['user']) && $_SESSION['user']['admin'] == 1) {
            $users = $this->userManager->findAll(); 

            require('./View/usersList.php');
        } else {
            require('./View/unauthorized.php');
        }
    }
}
?>