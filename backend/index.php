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
require_once __DIR__ . '/rest/services/AuthService.php';
require "middleware/AuthMiddleware.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::register('appointmentService', 'AppointmentService');
Flight::register('medicalHistoryService', 'MedicalHistoryService');
Flight::register('reviewService', 'ReviewService');
Flight::register('servicesService', 'ServicesService');
Flight::register('authService', 'AuthService');
Flight::register('auth_middleware', "AuthMiddleware");



Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return TRUE;
   } else {
       try {

           $token = Flight::request()->getHeader("Authentication");
      
           if(Flight::auth_middleware()->verifyToken($token))
                return TRUE;

           
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
   }
});

Flight::route('GET /debug', function() {
$decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

           print_r($decoded_token->user);

           Flight::set('user', $decoded_token->user);
    
});

Flight::route('GET /debug/user', function() {
    $user = Flight::get('user');
    header('Content-Type: application/json');
    echo json_encode($user, JSON_PRETTY_PRINT);
});

require_once __DIR__ . '/rest/routes/AppointmentRoutes.php';
require_once __DIR__ . '/rest/routes/MedicalHistoryRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewRoutes.php';
require_once __DIR__ . '/rest/routes/ServiceRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/AuthRoutes.php';

Flight::start();