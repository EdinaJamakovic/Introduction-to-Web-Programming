<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao{
    public function __construct(){
        parent::__construct('reviews');
    }

    public function getByDoctorId($id){
        $stmt = $this->connection->prepare(
            'SELECT r.rating, r.comment, a.doctor_id, u.first_name, u.last_name FROM reviews r 
            JOIN medical_history mh on r.history_id = mh.history_id
            JOIN appointments a on mh.appointment_id = a.appointment_id
            JOIN users u on a.patient_id = u.user_id
            WHERE a.doctor_id = :id'
        );
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>