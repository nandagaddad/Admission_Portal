
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . "/../Config/Database.php";
require __DIR__ . "/../Models/Admin.php";

class AuthController
{
    private $adminModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();

        $this->adminModel = new Admin($db);
    }

    public function login()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $admin = $this->adminModel->login($username, $password);
            
            if($admin)
            {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['username'] = $admin['username'];

                header("Location: /Admission_Portal/Views/dashboard/index.php");
                exit();
            }
            
            else
            {
                $_SESSION['error'] = "Invalid Username or Password";

                header("Location: ../views/auth/login.php");
                exit();
            }
        }
        
    }

    public function logout()
    {
        // Clear all session variables
        $_SESSION = [];
        
        // Delete session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Destroy the session
        session_destroy();

        header("Location: /Admission_Portal/Views/auth/login.php");

        exit();
    }
}

$auth = new AuthController();

if(isset($_POST['login']))
{   
    $auth->login();
    
}

if(isset($_GET['logout']))
{
    $auth->logout();
}

?>