<?php
require_once 'BaseDao.php';

class MedicalHistoryDao extends BaseDao{
    public function __construct(){
        parent::__construct('medical_history');
    }

    public function getByPatientId($id){
        $stmt = $this->connection->prepare(
            'SELECT * FROM medical_history mh 
            JOIN appointments a on mh.appointment_id = a.appointment_id
            JOIN users d on a.doctor_id = d.user_id
            JOIN services s on a.service_id = s.service_id 
            WHERE a.patient_id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>