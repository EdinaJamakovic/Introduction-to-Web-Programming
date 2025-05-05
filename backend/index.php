<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-errors.log');

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/rest/config.php';

require_once __DIR__ . '/rest/services/AppointmentService.php';
require_once __DIR__ . '/rest/services/MedicalHistoryService.php';
require_once __DIR__ . '/rest/services/ReviewService.php';
require_once __DIR__ . '/rest/services/ServicesService.php';
require_once __DIR__ . '/rest/services/UserService.php';

Flight::register('appointmentService', 'AppointmentService');
Flight::register('medicalHistoryService', 'MedicalHistoryService');
Flight::register('reviewService', 'ReviewService');
Flight::register('servicesService', 'ServicesService');
Flight::register('userService', 'UserService');

require_once __DIR__ . '/rest/routes/AppointmentRoutes.php';
require_once __DIR__ . '/rest/routes/MedicalHistoryRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/ServiceRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';

Flight::route('/', function() {
    echo 'Dental Clinic API';
});

Flight::start();