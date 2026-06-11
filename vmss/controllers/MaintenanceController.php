<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Maintenance.php';

class MaintenanceController {
    private $maintenanceModel;

    public function __construct($database) {
        $this->maintenanceModel = new Maintenance($database);
    }

    public function listAll() {
        Auth::checkAuthenticated();
        $records = $this->maintenanceModel->getAllRecords();
        require_once __DIR__ . '/../views/maintenance.php';
    }

    public function fileComplaint() {
        Auth::verifyRole(['Admin', 'FleetManager', 'Driver']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->maintenanceModel->logComplaint(
                $_POST['vehicle_id'], 
                $_SESSION['user_id'], 
                $_POST['complaint']
            );
            header("Location: /vmss/index.php?action=maintenance");
            exit();
        }
    }

    public function updateWorkOrder() {
        Auth::verifyRole(['Admin', 'Technician']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->maintenanceModel->executeCorrection(
                $_POST['record_id'],
                $_POST['cause'],
                $_POST['correction'],
                $_POST['parts_cost'],
                $_POST['labor_cost'],
                $_POST['status'],
                $_SESSION['user_id']
            );
            header("Location: /vmss/index.php?action=maintenance");
            exit();
        }
    }
}