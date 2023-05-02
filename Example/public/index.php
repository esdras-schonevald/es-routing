<?php

use Phprise\Routing\RouterConfiguration;
use Phprise\Routing\SimpleRouter;

include_once __DIR__ . '/../../vendor/autoload.php';

$configuration = RouterConfiguration::createFromAttributes(__DIR__ . '/../App/Controller');
$router = new SimpleRouter($configuration);

$router->execute();
