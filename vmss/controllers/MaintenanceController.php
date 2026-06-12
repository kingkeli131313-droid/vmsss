<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Maintenance.php';

class MaintenanceController {
    private $maintenanceModel;

    public function __construct($database) {
        // Initialize your data access model
        $this->maintenanceModel = new Maintenance($database);
    }

    // 🚀 FIX: Renamed from listAll to listLog to match your index.php routing parameters perfectly
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

        // Fetch vehicles if needed for a dropdown selector in the view
        $vehicles = [];
        try {
            $stmt = $database->query("SELECT id, license_plate, make, model FROM vehicles ORDER BY license_plate ASC");
            $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Graceful fallback
        }

        require_once __DIR__ . '/../views/maintenance.php';
    }

    // Alternative catch-all method name mapping to ensure reliability
    public function listAll() {
        $this->listLog();
    }

    // 🚀 FIX: Removed local /vmss/ folder prefixes from redirection headers
    public function fileComplaint() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Fallback value helper if session assignments are customized
            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;

            $this->maintenanceModel->logComplaint(
                $_POST['vehicle_id'], 
                $user_id, 
                $_POST['complaint']
            );
            
            // Redirects to root application route
            header("Location: index.php?action=maintenance");
            exit();
        }
    }

    // 🚀 FIX: Realigned to support your administrative triage dashboard edits seamlessly
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
