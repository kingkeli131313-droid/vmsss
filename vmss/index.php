<?php
// 1. Core system files configuration
require_once __DIR__ . '/config/database.php'; 
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/VehicleController.php';
require_once __DIR__ . '/controllers/MaintenanceController.php';

// 2. Connect to the Render Database instance
// (Note: Make sure the variable name here matches $db used below)
$db = Database::getConnection(); 

// ==========================================
// 🚀 PASTE THE AUTOMATED DATABASE INITIALIZER HERE
// ==========================================
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
    // Falls back gracefully if connection is initializing 
}
// ==========================================

// 3. Your existing Routing Logic down here (Keep your switch/case actions)
$action = isset($_GET['action']) ? $_GET['action'] : 'login_page';
// ... rest of your code continues normally
