<?php
use App\Core\App;

ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';

$app = new App();
$app->run();
