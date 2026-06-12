<?php
class AuthController {
    private $db;

    public function __construct($db) {
        $db = $db;
    }

    // 🚀 This is the rendering method index.php is looking for!
    public function showLoginForm() {
        include __DIR__ . '/../views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';

            // Hardcoded bypass workspace credentials
            if ($username === 'admin' && $password === 'admin123') {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user'] = 'admin';
                $_SESSION['role'] = 'admin';
                header('Location: index.php?action=dashboard');
                exit();
            } else {
                header('Location: index.php?action=login_page&error=invalid');
                exit();
            }
        }
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header('Location: index.php?action=login_page');
        exit();
    }
}
?>
