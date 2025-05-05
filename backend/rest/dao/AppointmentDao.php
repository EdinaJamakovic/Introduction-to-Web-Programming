<?php
require_once 'BaseDao.php';

class AppointmentDao extends BaseDao{
    public function __construct(){
        parent::__construct('appointments');
    }

    public function getByPatientId($id) {
        $stmt = $this->connection->prepare(
            "SELECT a.id as appointment_id, a.date, a.time, s.title as title, 
                    CONCAT(d.first_name, ' ', d.last_name) as doctor 
             FROM appointments a
             JOIN users d ON a.doctor_id = d.id
             JOIN services s ON a.service_id = s.id
             WHERE a.patient_id = :id AND a.status = 'scheduled'"
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    

    public function getByDoctorId($id, $stat){
        $stmt = $this->connection->prepare(
            "SELECT 
            a.id AS appointment_id,
            a.date,
            a.time,
            a.status,
            p.first_name,
            p.last_name,
            s.title
            FROM appointments a
            LEFT JOIN users p ON a.patient_id = p.id
            JOIN services s ON a.service_id = s.id
            WHERE a.doctor_id = :id AND a.status = :stat;"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':stat', $stat);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllFreeAppointments(){
        $stmt = $this->connection->prepare(
            "SELECT a.date, a.time, CONCAT(d.first_name, ' ', d.last_name) as doctor FROM appointments a 
            JOIN users d ON a.doctor_id = d.id 
            WHERE status = 'free'"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>