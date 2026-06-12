<?php
class AuthController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Displays the login page view
    public function showLoginForm() {
        include __DIR__ . '/../views/login.php';
    }

    // Handles the login verification form submission
    public function login() {
        // Force session to start so the browser remembers you
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // 🔐 System Admin Bypass Credentials
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['user'] = 'admin';
                $_SESSION['role'] = 'admin';
                
                // Save session changes immediately before redirecting
                session_write_close(); 
                
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                header('Location: index.php?action=login_page&error=invalid');
                exit();
            }
        }
        
        // Fallback if accessed incorrectly
        header('Location: index.php?action=login_page');
        exit();
    }

    // Clears everything when logging out
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: index.php?action=login_page');
        exit();
    }
}
?>
