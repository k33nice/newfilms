<?php

date_default_timezone_set('Europe/Kiev');

require_once __DIR__ . '/../vendor/autoload.php';
$DBConfig = include_once __DIR__ . '/../etc/database.php';

AppFactory::initPropel($DBConfig);
AppFactory::initApp();


AppFactory::$slimInstance->run();

?>