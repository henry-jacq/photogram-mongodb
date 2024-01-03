<?php

include __DIR__ . '/../bootstrap.php';

use App\Core\View;
use App\Core\Config;

$config = require __DIR__ . '/../config/app.php';

$c = new Config($config);
$v = new View($c);

$args = [
    'title' => 'Home page'
];

$v->createPage('home/home', $args)->render();