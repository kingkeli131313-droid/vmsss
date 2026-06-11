<?php
// Securely lock the absolute directory path to prevent Windows backslash errors
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);

require_once BASE_PATH . 'config' . DIRECTORY_SEPARATOR . 'database.php';
require_once BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR . 'AuthController.php';
require_once BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR . 'VehicleController.php';
require_once BASE_PATH . 'controllers' . DIRECTORY_SEPARATOR . 'MaintenanceController.php';

$database = new Database();
$db = $database->getConnection();

$action = isset($_GET['action']) ? $_GET['action'] : 'login_page';

switch ($action) {
    case 'login_page':
        require_once BASE_PATH . 'views' . DIRECTORY_SEPARATOR . 'login.php';
        break;
    case 'login_submit':
        $controller = new AuthController($db);
        $controller->login();
        break;
    case 'logout':
        $controller = new AuthController($db);
        $controller->logout();
        break;
    case 'vehicles':
        $controller = new VehicleController($db);
        $controller->listAll();
        break;
    case 'add_vehicle':
        $controller = new VehicleController($db);
        $controller->addVehicle();
        break;
    case 'maintenance':
        $controller = new MaintenanceController($db);
        $controller->listAll();
        break;
    case 'add_complaint':
        $controller = new MaintenanceController($db);
        $controller->fileComplaint();
        break;
    case 'update_work_order':
        $controller = new MaintenanceController($db);
        $controller->updateWorkOrder();
        break;
    default:
        require_once BASE_PATH . 'views' . DIRECTORY_SEPARATOR . 'login.php';
        break;
}
