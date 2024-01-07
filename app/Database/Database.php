<?php

namespace App\Database;

use App\Interfaces\DatabaseConnectorInterface;

class Database
{
    private static $instance = null;

    private function __construct(private readonly DatabaseConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    public static function getInstance(DatabaseConnectorInterface $connector)
    {
        if (!self::$instance) {
            self::$instance = new self($connector);
        }
        return self::$instance;
    }

    public function query($query, $params = [])
    {
        return $this->connector->query($query, $params);
    }

    public function execute($query, $params = [])
    {
        return $this->connector->execute($query, $params);
    }

    private function __clone()
    {
        // Prevent cloning of this object
    }

    private function __wakeup()
    {
        // Prevent unserializing of this object
    }
}
