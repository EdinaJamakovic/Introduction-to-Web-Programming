<?php
require_once "BaseService.php";
require_once "./rest/dao/MedicalHistoryDao.php";

class MedicalHistoryService extends BaseService{
    protected $dao;

    public function __construct(){
        $dao = new MedicalHistoryDao();
        parent::__construct($dao);
    }

    public function getForPatient($id){
        return $this->dao->getByPatientId($id);
    }
}

?>