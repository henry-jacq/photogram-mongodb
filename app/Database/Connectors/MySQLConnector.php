<?php

namespace App\Database\Connectors;

use \PDO;
use App\Interfaces\DatabaseConnectorInterface;

class MySQLConnector implements DatabaseConnectorInterface
{
    protected $table = null;
    protected $fetchMode = null;

    private function __construct(public readonly PDO $conn)
    {
        $this->fetchMode = $this->conn::FETCH_ASSOC;
    }
    
    public function getConnection()
    {
        return $this->conn;
    }

    public function query($query, $params = [])
    {
    }

    public function execute($query, $params = [])
    {
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
