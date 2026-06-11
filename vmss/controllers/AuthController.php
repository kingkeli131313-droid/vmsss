<?php
require_once __DIR__ . '/../core/Auth.php';

class AuthController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function login() {
        Auth::initSession();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header("Location: /vmss/index.php?action=vehicles");
                exit();
            } else {
                header("Location: /vmss/index.php?action=login_page&error=Invalid Credentials");
                exit();
            }
        }
    }

    public function logout() {
        Auth::initSession();
        $_SESSION = [];
        session_destroy();
        header("Location: /vmss/index.php?action=login_page");
        exit();
    }
}