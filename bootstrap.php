<?php

use Dotenv\Dotenv;

require 'vendor/autoload.php';
require 'config/constants.php';
require 'config/helper.php';

ini_set('display_errors', true);

$dotenv = Dotenv::createImmutable(APP_PATH);
$dotenv->load();
