<?php
require_once "./services/AppointmentService.php";

$service = new AppointmentService();
print_r($dao->getByPatientId(3));


?>