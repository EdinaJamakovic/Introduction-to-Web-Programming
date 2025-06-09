<?php
require_once 'BaseDao.php';

class UserDao extends BaseDao{
    public function __construct(){
        parent::__construct('users');
    }

    public function getAllDoctors(){
        $stmt->connection->prepare("SELECT * FROM users WHERE role = 'doctor'");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>