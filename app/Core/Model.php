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
    public function findById(string $id, $index = '_id', $genMongoId = true, $multiple = false)
    {
        if ($genMongoId) {
            $id = $this->createMongoId($id);
        }

        if ($multiple) {
            return $this->collection->find([
                $index => $id
            ]);
        }
        
        return $this->collection->findOne([
            $index => $id
        ]);
    }

    // Read operation
    public function findAll()
    {
        return $this->collection->find();
    }
    
    // Update operation
    public function update($id, $data, $index = '_id')
    {
        return $this->collection->updateOne([
            $index => $this->createMongoId($id)
        ], ['$set' => $data]);
    }

    // Delete operation
    public function delete($id, $index = '_id')
    {
        $this->collection->deleteOne([
            $index => $this->createMongoId($id)
        ]);
    }

    // Additional methods can be added based on your requirements
}
