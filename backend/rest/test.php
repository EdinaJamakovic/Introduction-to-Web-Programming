<?php
require_once "./dao/UserDao.php";

$dao = new UserDao();
print_r($dao->getAll());


?>