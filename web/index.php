<?php

define("ROOT_PATH", __DIR__ . "/..");

require_once '../vendor/autoload.php';

$app = new Bicycle\Core\Application();

try {
    $bootstrap = new Bicycle\App\Bootstrap($app);
    $bootstrap->bootstrap();

    $app->handle();

} catch (\Exception $e) {
    $response = $app->createExceptionResponse($e);
    $app->sendResponse(json_encode($response), ['Content-Type: application/json']);
}