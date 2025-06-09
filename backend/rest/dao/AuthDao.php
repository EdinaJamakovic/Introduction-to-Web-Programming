<?php
require_once __DIR__ . '/BaseDao.php';


class AuthDao extends BaseDao {
   protected $table_name;


   public function __construct() {
       $this->table_name = "users";
       parent::__construct($this->table_name);
   }

   public function getByUserEmail($email) {
       $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
       $stmt = $this->connection->prepare($query);
       $stmt->bindParam(":email", $email);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function getAllDoctors(){
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE role = 'doctor'");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>