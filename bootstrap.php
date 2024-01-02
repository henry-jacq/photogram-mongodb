<?php

use Dotenv\Dotenv;

include 'vendor/autoload.php';
include 'config/constants.php';
include 'config/helper.php';

ini_set('display_errors', true);

$dotenv = Dotenv::createImmutable(APP_PATH);
$dotenv->load();
