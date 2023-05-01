<?php

use App\Routing\RouterConfiguration;
use App\Routing\SimpleRouter;

include_once __DIR__ . '/../../vendor/autoload.php';

$configuration = RouterConfiguration::createFromAttributes(__DIR__ . '/../App/Controllers');
$router = new SimpleRouter($configuration);

$router->execute();
