<?php
require_once "BaseService.php";
require_once "./rest/dao/ReviewDao.php";

class ReviewService extends BaseService{
    public function __construct(){
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function getByDoctorId($id){
        return $this->dao->getByDoctorId($id);
    }
}
?>