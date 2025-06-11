<?php
require_once __DIR__ . '/BaseService.php';
require_once "./rest/dao/AppointmentDao.php";

class AppointmentService extends BaseService {

    public function __construct() {
        $dao = new AppointmentDao();
        parent::__construct($dao);
    }

    public function getByPatientId($id) {
        if (!is_numeric($id)) {
            throw new Exception("Patient ID must be numeric", 400);
        }
        return $this->dao->getByPatientId($id);
    }

    public function getByDoctorId($id, $stat) {
        if (!is_numeric($id)) {
            throw new Exception("Doctor ID must be numeric", 400);
        }
        
        $validStatuses = ['scheduled', 'completed', 'cancelled', 'free'];
        if (!in_array($stat, $validStatuses)) {
            throw new Exception("Invalid appointment status", 400);
        }
        
        return $this->dao->getByDoctorId($id, $stat);
    }

    public function getFreeAppointments() {
        return $this->dao->getAllFreeAppointments();
    }

    public function create($data) {
        $requiredFields = ['patient_id', 'doctor_id', 'date', 'time'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field", 400);
            }
        }

        if (!is_numeric($data['patient_id']) || !is_numeric($data['doctor_id'])) {
            throw new Exception("Patient and Doctor IDs must be numeric", 400);
        }

        if (!strtotime($data['date'])) {
            throw new Exception("Invalid date format", 400);
        }

        return parent::create($data);
    }

    public function update($id, $data) {
        if (!is_numeric($id)) {
            throw new Exception("Invalid appointment ID", 400);
        }

        if (isset($data['status'])) {
            $validStatuses = ['scheduled', 'completed', 'cancelled'];
            if (!in_array($data['status'], $validStatuses)) {
                throw new Exception("Invalid appointment status", 400);
            }
        }

        return parent::update($id, $data);
    }
}
?>