<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../models/Vehicle.php';

class VehicleController {
    private $vehicleModel;

    public function __construct($database) {
        $this->vehicleModel = new Vehicle($database);
    }

    public function listAll() {
        Auth::checkAuthenticated();
        $vehicles = $this->vehicleModel->getAllVehicles();
        require_once __DIR__ . '/../views/vehicles.php';
    }

    public function addVehicle() {
        Auth::verifyRole(['Admin', 'FleetManager']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->vehicleModel->create($_POST);
            header("Location: /vmss/index.php?action=vehicles");
            exit();
        }
    }
}