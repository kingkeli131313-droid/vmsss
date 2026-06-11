<?php
class Maintenance {
    private $db;
    private $table = "maintenance_records";

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAllRecords() {
        $query = "SELECT m.*, v.license_plate, v.make, v.model, u.username as reporter, t.username as technician 
                  FROM " . $this->table . " m
                  JOIN vehicles v ON m.vehicle_id = v.id
                  JOIN users u ON m.reported_by = u.id
                  LEFT JOIN users t ON m.assigned_technician_id = t.id
                  ORDER BY m.logged_date DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function logComplaint($vehicle_id, $reported_by, $complaint) {
        $query = "INSERT INTO " . $this->table . " (vehicle_id, reported_by, complaint, status) VALUES (:vehicle_id, :reported_by, :complaint, 'Logged')";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':vehicle_id' => intval($vehicle_id),
            ':reported_by' => intval($reported_by),
            ':complaint' => htmlspecialchars(strip_tags($complaint))
        ]);
    }

    public function executeCorrection($id, $cause, $correction, $parts_cost, $labor_cost, $status, $tech_id) {
        $query = "UPDATE " . $this->table . " 
                  SET cause = :cause, correction = :correction, parts_cost = :parts_cost, labor_cost = :labor_cost, status = :status, assigned_technician_id = :tech_id, completion_date = IF(:status = 'Completed', NOW(), completion_date)
                  WHERE id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => intval($id),
            ':cause' => htmlspecialchars(strip_tags($cause)),
            ':correction' => htmlspecialchars(strip_tags($correction)),
            ':parts_cost' => floatval($parts_cost),
            ':labor_cost' => floatval($labor_cost),
            ':status' => $status,
            ':tech_id' => intval($tech_id)
        ]);
    }
}