<?php
// 🚀 1. FORCE ROUTER SESSION INITIALIZATION BEFORE ANY OUTPUT
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable Full Error Reporting to surface any configuration mismatches instantly
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Core System Framework Configurations
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/VehicleController.php';
require_once __DIR__ . '/controllers/MaintenanceController.php';

// 3. Establish Secure Cloud Database Connection
$db = null;
try {
    $db = Database::getConnection();
} catch (\Exception $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// 🚀 AUTOMATED POSTGRESQL DATABASE INITIALIZER
if ($db) {
    try { 
        $tableCheck = $db->query("SELECT 1 FROM information_schema.tables WHERE table_name = 'vehicles'"); 
        if ($tableCheck->rowCount() == 0) { 
            $setupSQL = " 
            CREATE TABLE IF NOT EXISTS users ( 
                id SERIAL PRIMARY KEY, 
                username VARCHAR(50) UNIQUE NOT NULL, 
                password_hash VARCHAR(255) NOT NULL, 
                role VARCHAR(20) DEFAULT 'admin'
            ); 
            CREATE TABLE IF NOT EXISTS vehicles ( 
                id SERIAL PRIMARY KEY, 
                license_plate VARCHAR(20) UNIQUE NOT NULL, 
                vin VARCHAR(50) UNIQUE NOT NULL, 
                make VARCHAR(50) NOT NULL, 
                model VARCHAR(50) NOT NULL, 
                manufacture_year INT NOT NULL, 
                current_odometer INT NOT NULL, 
                dvla_roadworthy_expiry DATE NOT NULL, 
                status VARCHAR(20) DEFAULT 'Active', 
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ); 
            CREATE TABLE IF NOT EXISTS maintenance ( 
                id SERIAL PRIMARY KEY, 
                vehicle_id INT REFERENCES vehicles(id) ON DELETE CASCADE, 
                service_details TEXT NOT NULL, 
                service_date DATE NOT NULL, 
                cost DECIMAL(10,2) NOT NULL, 
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            );"; 
            $db->exec($setupSQL); 
        } 
    } catch (\Exception $e) { 
        // Falls back gracefully if database is preparing transactions
    }
}

// 4. System Routing Layer / Application Controller Core Initialization
$action = isset($_GET['action']) ? $_GET['action'] : 'login_page';
$authController = new AuthController($db);
$vehicleController = new VehicleController($db);
$maintenanceController = new MaintenanceController($db);

switch ($action) {
    case 'login':
        $authController->login();
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'dashboard':
    case 'vehicles': // 🚀 Catch-all for unified layout template requests
        $vehicleController->listAll();
        break;
        
    case 'save_vehicle':
        $vehicleController->createVehicle();
        break;
        
    case 'maintenance_log':
    case 'maintenance': // 🚀 Unified alignment path for Service Triage log entries
        if (method_exists($maintenanceController, 'listLog')) {
            $maintenanceController->listLog();
        } else if (method_exists($maintenanceController, 'index')) {
            $maintenanceController->index();
        }
        break;
        
    case 'save_maintenance':
        $maintenanceController->createLog();
        break;
        
    case 'login_page':
    default:
        // Safely prevents loop overlap by enforcing session routing state
        if (isset($_SESSION['user'])) {
            header('Location: index.php?action=dashboard');
            exit();
        }
        
        if (method_exists($authController, 'showLoginForm')) {
            $authController->showLoginForm();
        } else if (method_exists($authController, 'index')) {
            $authController->index();
        } else {
            die("Authentication view rendering method missing inside AuthController.php");
        }
        break;
}
?>
