<?php
require_once "BaseService.php";
require_once "./rest/dao/ServicesDao.php";

class ServicesService extends BaseService{
    public function __construct(){
        $dao = new ServicesDao();
        parent::__construct($dao);
    }
}
?>