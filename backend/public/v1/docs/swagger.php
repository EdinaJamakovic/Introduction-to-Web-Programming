<?php
require __DIR__ . '/../../../../vendor/autoload.php';

define('LOCALSERVER', 'http://localhost:83/project/backend/');
define('PRODSERVER', 'https://add-production-server-after-deployment/backend/');

$isLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);
define('BASE_URL', $isLocal ? LOCALSERVER : PRODSERVER);

$openapi = \OpenApi\Generator::scan([
    __DIR__ . '/doc_setup.php',
    __DIR__ . '/../../../rest/routes'
]);

header('Content-Type: application/json');
echo $openapi->toJson();
?>
