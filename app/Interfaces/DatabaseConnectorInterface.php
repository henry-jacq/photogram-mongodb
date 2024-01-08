<?php

namespace App\Interfaces;

use \PDO;

interface DatabaseConnectorInterface
{
    public static function getConnection(PDO $conn);
    public function execute($query, $params = []);
}