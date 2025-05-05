<?php
require_once "AppointmentService.php";
$service = new AppointmentService();
print_r($service->getFreeAppointments());

?>