<?php
require_once 'BaseDao.php';

class MedicalHistoryDao extends BaseDao{
    public function __construct(){
        parent::__construct('medical_history');
    }

    public function getByPatientId($id){
        $stmt = $this->connection->prepare(
            'SELECT mh.*, a.date as appointment_date, a.time as appointment_time, 
                    d.first_name as doctor_first_name, d.last_name as doctor_last_name, 
                    s.title as service_title
            FROM medical_history mh 
            JOIN appointments a on mh.appointment_id = a.id
            JOIN users d on a.doctor_id = d.id
            JOIN services s on a.service_id = s.id 
            WHERE a.patient_id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
}
?>