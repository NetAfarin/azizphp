<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header_remove('X-Powered-By');
ini_set('expose_php', false);
date_default_timezone_set("Asia/Tehran");
session_start();

if (isset($_GET['lang']) && in_array($_GET['lang'], ['fa', 'en'])) {
    $_SESSION['lang'] = $_GET['lang'];
}


if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'fa';
}

define('APP_LANG', $_SESSION['lang'] ?? 'fa');
define('APP_DIRECTION', APP_LANG === 'fa' ? 'rtl' : 'ltr');

require_once __DIR__ . '/../app/Helpers/csrf.php';
require_once __DIR__ . '/../app/Helpers/functions.php';
require_once __DIR__ . '/../app/Helpers/jdf.php';
require_once __DIR__ . '/../app/Helpers/lang.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

use App\Core\App;
use App\Core\Logger;

Logger::configureFromConstant();

////log test
//Logger::info('User logged in', ['user_id' => 12]);
//Logger::error('Database connection failed', ['host' => 'localhost']);
//Logger::critical('Server down', ['time' => time()]);

$app = new App();
$app->run();
