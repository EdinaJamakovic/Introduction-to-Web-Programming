<?php
require_once __DIR__ . '/BaseService.php';
require_once "./rest/dao/AppointmentDao.php";

class AppointmentService extends BaseService{

    public function __construct(){
        $dao = new AppointmentDao();
        parent::__construct($dao);
    }

    public function getByPatientId($id){
        return $this->dao->getByPatientId($id);
    }

    public function getByDoctorId($id, $stat){
        return $this->dao->getByDoctorId($id, $stat);
    }

    public function getFreeAppointments(){
        return $this->dao->getAllFreeAppointments();
    }
}

?>