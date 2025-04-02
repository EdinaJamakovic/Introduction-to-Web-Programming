<?php
require_once 'AppointmentDao.php';

$dao = new AppointmentDao();
print_r($dao->getFreeAppointments());

?>