<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao{
    public function __construct(){
        parent::__construct('reviews');
    }

    public function getByDoctorId($id){
        $stmt = $this->connection->prepare(
            'SELECT * FROM reviews r 
            JOIN medical_history mh on r.history_id = mh.id
            JOIN appointments a on mh.appointment_id = a.id
            JOIN users u on a.patient_id = u.id
            WHERE a.doctor_id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>