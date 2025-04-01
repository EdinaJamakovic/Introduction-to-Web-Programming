<?php
require_once 'BaseDao.php';

class ReviewDao extends BaseDao{
    public function __construct(){
        parent::_construct('reviews');
    }

    public function getByDoctorId($id){
        $stmt->connection->prepare('SELECT * FROM reviews WHERE doctor_id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
}



?>