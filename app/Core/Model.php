<?php

namespace App\Core;

use App\Core\MongoDB;
use Exception;

class Model
{
    protected $db;
    protected $name;
    protected $collection;

    public function __construct(MongoDB $db, $collection)
    {
        $this->db = $db;
        $this->name = $collection;
        $this->ensureCollectionExists();
        $this->db->selectCollection($collection);
        $this->collection = $db->getCollection();
    }

    // Ensure the collection exists, create if it doesn't
    public function ensureCollectionExists()
    {
        try {
            $output = $this->db->selectCollection($this->name)->findOne();
            if ($output == null) {
                $this->db->createCollection($this->name);
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function createMongoId(string $id)
    {
        return $this->db->createMongoId($id);
    }

    public function findOne(array $data)
    {
        return $this->collection->findOne($data);
    }

    // Create operation
    public function create($data)
    {
        return $this->collection->insertOne($data);
    }

    // Read operation
    public function findById($id)
    {
        return $this->collection->findOne([
            '_id' => $this->createMongoId($id)
        ]);
    }

    // Read operation
    public function findAll()
    {
        return $this->collection->find();
    }
    
    // Update operation
    public function update($id, $data)
    {
        $this->collection->updateOne([
            '_id' => $this->createMongoId($id)
        ], ['$set' => $data]);
    }

    // Delete operation
    public function delete($id)
    {
        $this->collection->deleteOne([
            '_id' => $this->createMongoId($id)
        ]);
    }

    // Additional methods can be added based on your requirements
}
