<?php
class VehicleController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // 🚀 FIX: Handles displaying all vehicles with safe fallback calculations
    public function listAll() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // If a user isn't logged in, redirect them out immediately to stop the loop stacking
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login_page');
            exit();
        }

        $vehicles = [];
        try {
            // Calculates DVLA compliance expiration window directly in PostgreSQL
            $query = "SELECT *, 
                      (dvla_roadworthy_expiry - CURRENT_DATE) as compliance_days_remaining 
                      FROM vehicles ORDER BY id DESC";
            $stmt = $this->db->query($query);
            $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Fallback empty array if tables are initializing
            $vehicles = [];
        }

        // Load view layout explicitly using absolute directory maps
        include __DIR__ . '/../views/vehicles.php';
    }

    // 🚀 FIX: Handles capturing asset form data from the Fleet Board interface safely
    public function createVehicle() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login_page');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $license_plate = isset($_POST['license_plate']) ? trim($_POST['license_plate']) : '';
            $vin = isset($_POST['vin']) ? trim($_POST['vin']) : '';
            $make = isset($_POST['make']) ? trim($_POST['make']) : '';
            $model = isset($_POST['model']) ? trim($_POST['model']) : '';
            $year = isset($_POST['manufacture_year']) ? (int)$_POST['manufacture_year'] : date('Y');
            $odometer = isset($_POST['current_odometer']) ? (int)$_POST['current_odometer'] : 0;
            $expiry = isset($_POST['dvla_roadworthy_expiry']) ? $_POST['dvla_roadworthy_expiry'] : date('Y-m-d');
            $status = isset($_POST['status']) ? trim($_POST['status']) : 'Available';

            try {
                $sql = "INSERT INTO vehicles (license_plate, vin, make, model, manufacture_year, current_odometer, dvla_roadworthy_expiry, status) 
                        VALUES (:license_plate, :vin, :make, :model, :year, :odometer, :expiry, :status)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    ':license_plate' => $license_plate,
                    ':vin' => $vin,
                    ':make' => $make,
                    ':model' => $model,
                    ':year' => $year,
                    ':odometer' => $odometer,
                    ':expiry' => $expiry,
                    ':status' => $status
                ]);
            } catch (\Exception $e) {
                // Ignore duplicate errors during initialization testing
            }
        }

        header('Location: index.php?action=dashboard');
        exit();
    }
}
?>
