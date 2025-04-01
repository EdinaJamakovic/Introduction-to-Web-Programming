<?php
require_once 'BaseDao.php';

class AppointmentDao extends BaseDao{
    public function __construct(){
        parent::construct('appointments');
    }

    public function getByPatientId($id){
        $stmt->connection->prepare('SELECT * FROM appointments WHERE patient_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByDoctorId($id){
        $stmt->connection->prepare('SELECT * FROM appointments WHERE doctor_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

}

?>