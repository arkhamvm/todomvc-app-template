<?php

declare(strict_types=1);

namespace App\config;

use App\base\Application;

mb_internal_encoding('UTF-8');
mb_regex_encoding   ('UTF-8');
date_default_timezone_set('Asia/Vladivostok');

$root = dirname(dirname(__DIR__));

defined('ROOT') or define('ROOT', $root);
defined('APP_ROOT') or define('APP_ROOT', $root . '/app');
defined('DEBUG') or define('DEBUG', file_exists(ROOT . '/.dev'));

error_reporting(E_ALL);
ini_set('log_errors', (string)true);
ini_set('display_errors', (string)DEBUG);
ini_set('display_startup_errors', (string)DEBUG);

require($root . '/vendor/autoload.php');

(new Application())->run();
