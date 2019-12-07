<?php

require __DIR__ . '/../vendor/autoload.php';

$appl = \App\Application::getInstance( __DIR__ . "/../" );
$appl->prepare();
$appl->run();
