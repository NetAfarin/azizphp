<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/lang.php';

use App\Core\App;

$app = new App();
$app->run();
