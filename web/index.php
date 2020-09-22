<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../etc/autoload.php';

use etc\app\App;

App::i()->Run();
