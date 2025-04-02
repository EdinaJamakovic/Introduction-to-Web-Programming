<?php
require_once 'BaseDao.php';

class AppointmentDao extends BaseDao{
    public function __construct(){
        parent::__construct('appointments');
    }

    public function getByPatientId($id){
        $stmt = $this->connection->prepare('SELECT * FROM appointments WHERE patient_id = :id AND status = \'scheduled\' ');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByDoctorId($id, $stat){
        $stmt = $this->connection->prepare('SELECT * FROM appointments WHERE doctor_id = :id AND status = :stat');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':stat', $stat);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllFreeAppointments($id){
        $stmt = $this->connection->prepare('SELECT * FROM appointments WHERE doctor_id = :id AND status = \'free\'');
        $stmt->bindParam(':id', $id)
        $stmt->execute();
        return $stmt->fetchAll();
    }

}

?>