<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Maintenance.php';

class MaintenanceController {
    private $maintenanceModel;
    private $db; // 🚀 Added class property to store database connection cleanly

    public function __construct($database) {
        $this->db = $database; // 🚀 Save connection here for internal methods
        $this->maintenanceModel = new Maintenance($database);
    }

    public function listLog() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Enforce secure workspace authentication gate
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login_page');
            exit();
        }

        // Fetch logs utilizing your analytical metrics model
        $records = [];
        try {
            $records = $this->maintenanceModel->getAllRecords();
        } catch (\Exception $e) {
            $records = [];
        }

        // Fetch vehicles using the proper internal class connection
        $vehicles = [];
        try {
            // 🚀 FIX: Swapped out broken variable $database for the internal connection property $this->db
            if (isset($this->db)) {
                $stmt = $this->db->query("SELECT id, license_plate, make, model FROM vehicles ORDER BY license_plate ASC");
                $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (\Exception $e) {
            $vehicles = []; // Graceful fallback
        }

        require_once __DIR__ . '/../views/maintenance.php';
    }

    // Alternative catch-all method name mapping to ensure reliability
    public function listAll() {
        $this->listLog();
    }

    public function fileComplaint() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

            $this->maintenanceModel->logComplaint(
                $_POST['vehicle_id'], 
                $user_id, 
                $_POST['complaint']
            );
            
            header("Location: index.php?action=maintenance");
            exit();
        }
    }

    public function updateWorkOrder() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

            $this->maintenanceModel->executeCorrection(
                $_POST['record_id'],
                $_POST['cause'],
                $_POST['correction'],
                $_POST['parts_cost'],
                $_POST['labor_cost'],
                $_POST['status'],
                $user_id
            );
            
            header("Location: index.php?action=maintenance");
            exit();
        }
    }
}
?>
