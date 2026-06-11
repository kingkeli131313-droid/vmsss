<?php
class Auth {
    public static function initSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start([
                'cookie_lifetime' => 86400,
                'cookie_secure' => false, // Set to true when running under active production SSL certificates
                'cookie_httponly' => true,
                'cookie_samesite' => 'Strict'
            ]);
        }
    }

    public static function checkAuthenticated() {
        self::initSession();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /vmss/index.php?action=login_page");
            exit();
        }
    }

    public static function verifyRole($allowedRoles) {
        self::checkAuthenticated();
        if (!in_array($_SESSION['role'], $allowedRoles)) {
            http_response_code(403);
            die("Unauthorized Access: Your account profile does not retain permissions for this endpoint.");
        }
    }
}