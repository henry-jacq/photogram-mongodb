<?php

namespace App\Database\Connectors;

use App\Interfaces\DatabaseConnectorInterface;
use MongoDB\Client as MongoClient;

class MongoDBConnector implements DatabaseConnectorInterface
{
    protected $collection = null;
    protected $fetchMode = null;

    private function __construct(public readonly MongoClient $client)
    {
        $this->fetchMode = $this->client::TYPE_DOCUMENT;
    }

    public function getConnection()
    {
        return $this->client;
    }

    public function query($query, $params = [])
    {
        return $this->collection->find($query, $this->fetchMode);
    }

    public function execute($query, $params = [])
    {
        return $this->collection->insertOne($query);
    }

    private function __clone()
    {
        // Prevent cloning of this object
    }

    private function __wakeup()
    {
        // Prevent unserializing of this object
    }

    private function __destruct()
    {
        $this->client->close();
    }
}
