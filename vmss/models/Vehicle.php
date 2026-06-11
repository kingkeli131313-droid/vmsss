<?php
class Vehicle {
    private $db;
    private $table = "vehicles";

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllVehicles() {
        $query = "SELECT *, DATEDIFF(dvla_roadworthy_expiry, CURDATE()) as compliance_days_remaining FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (license_plate, vin, make, model, manufacture_year, current_odometer, dvla_roadworthy_expiry, status) VALUES (:license_plate, :vin, :make, :model, :manufacture_year, :current_odometer, :dvla_roadworthy_expiry, :status)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':license_plate' => htmlspecialchars(strip_tags($data['license_plate'])),
            ':vin' => htmlspecialchars(strip_tags($data['vin'])),
            ':make' => htmlspecialchars(strip_tags($data['make'])),
            ':model' => htmlspecialchars(strip_tags($data['model'])),
            ':manufacture_year' => intval($data['manufacture_year']),
            ':current_odometer' => intval($data['current_odometer']),
            ':dvla_roadworthy_expiry' => $data['dvla_roadworthy_expiry'],
            ':status' => $data['status']
        ]);
    }
}