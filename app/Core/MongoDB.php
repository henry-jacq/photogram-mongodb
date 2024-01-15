<?php

namespace App\Core;

use \Exception;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;
use MongoDB\Model\BSONDocument;
use MongoDB\Model\IndexInfoIterator;

class MongoDB
{
    private ?Database $db = null;
    private ?Collection $collection = null;

    private function __construct(private Client $client, string $dbName)
    {
        try {
            $this->client = $client;
            $this->selectDatabase($dbName);
        } catch (\Throwable $e) {
            throw new Exception("Failed to connect to MongoDB: " . $e->getMessage());
        }
    }

    public static function getInstance(Client $client, $dbName)
    {
        return new self($client, $dbName);
    }

    public function getDatabases(): array
    {
        try {
            $databases = $this->client->listDatabases();
            $databaseNames = [];
            foreach ($databases as $database) {
                $databaseNames[] = $database->getName();
            }
            return $databaseNames;
        } catch (\Throwable $e) {
            throw new Exception("Failed to retrieve databases: " . $e->getMessage());
        }
    }

    public function selectDatabase(string $db): self
    {
        $this->db = $this->client->selectDatabase($db);
        return $this;
    }

    public function createCollection(string $collectionName): bool
    {
        if (!$this->db) {
            throw new Exception("No database selected.");
        }

        try {
            $result = $this->db->createCollection($collectionName);
            return $result['ok'] == 1;
        } catch (\Throwable $e) {
            throw new Exception("Failed to create collection: " . $e->getMessage());
        }
    }

    public function dropCollection(string $collectionName): bool
    {
        if (!$this->db) {
            throw new Exception("No database selected.");
        }

        try {
            $result = $this->db->dropCollection($collectionName);
            return $result['ok'] == 1;
        } catch (\Throwable $e) {
            throw new Exception("Failed to drop collection: " . $e->getMessage());
        }
    }
    
    public function selectCollection(string $collectionName): self
    {
        if (!$this->db) {
            throw new Exception("No database selected.");
        }

        $this->collection = $this->db->selectCollection($collectionName);
        return $this;
    }

    public function getCollection(): Collection
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        return $this->collection;
    }

    public function createMongoId(string $id)
    {
        return new ObjectId($id);
    }

    public function find($filter = [], $options = []): array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $cursor = $this->collection->find($filter, $options);
            $results = [];
            foreach ($cursor as $document) {
                $results[] = $document;
            }
            return $results;
        } catch (\Throwable $e) {
            throw new Exception("Find operation failed: " . $e->getMessage());
        }
    }

    public function findOne($filter = [], $options = []): BSONDocument|null
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            return $this->collection->findOne($filter, $options);
        } catch (\Throwable $e) {
            throw new Exception("FindOne operation failed: " . $e->getMessage());
        }
    }

    public function findOneAndReplace($filter, $replacement, $options = []): ?string
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            // Find the document before replacement
            $documentBeforeReplacement = $this->collection->findOne($filter);

            // Replace the document and retrieve the original document
            $replacedDocument = $this->collection->findOneAndReplace($filter, $replacement, $options);

            if ($documentBeforeReplacement) {
                // Retrieve and return the ID of the document before replacement
                return (string) $documentBeforeReplacement['_id'];
            }

            return null; // No document found before replacement
        } catch (\Throwable $e) {
            throw new Exception("FindOneAndReplace operation failed: " . $e->getMessage());
        }
    }

    public function findOneAndUpdate($filter, $update, $options = []): ?string
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            // Find the document before update
            $documentBeforeUpdate = $this->collection->findOne($filter);

            // Update the document and retrieve the original document
            $updatedDocument = $this->collection->findOneAndUpdate($filter, $update, $options);

            if ($documentBeforeUpdate) {
                // Retrieve and return the ID of the document before update
                return (string) $documentBeforeUpdate['_id'];
            }

            return null; // No document found before update
        } catch (\Throwable $e) {
            throw new Exception("FindOneAndUpdate operation failed: " . $e->getMessage());
        }
    }

    public function findOneAndDelete($filter, $options = []): ?array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            // Find the document before deletion
            $documentBeforeDeletion = $this->collection->findOne($filter);

            // Delete the document and retrieve the original document
            $deletedDocument = $this->collection->findOneAndDelete($filter, $options);

            if ($documentBeforeDeletion) {
                // Retrieve and return the ID of the document before deletion
                return (string) $documentBeforeDeletion['_id'];
            }

            return null; // No document found before deletion
        } catch (\Throwable $e) {
            throw new Exception("FindOneAndDelete operation failed: " . $e->getMessage());
        }
    }

    public function insertOne(array $document): mixed
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $result = $this->collection->insertOne($document);
            return $result->getInsertedId();

        } catch (\Throwable $e) {
            throw new Exception("InsertOne operation failed: " . $e->getMessage());
        }
    }

    public function insertMany(array $documents): array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $result = $this->collection->insertMany($documents);
            $insertedIds = [];

            foreach ($result->getInsertedIds() as $insertedId) {
                $insertedIds[] = (string) $insertedId;
            }

            return $insertedIds;
        } catch (\Throwable $e) {
            throw new Exception("InsertMany operation failed: " . $e->getMessage());
        }
    }

    public function updateOne($filter, $update, $options = []): void
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $this->collection->updateOne($filter, $update, $options);
        } catch (\Throwable $e) {
            throw new Exception("UpdateOne operation failed: " . $e->getMessage());
        }
    }

    public function updateMany($filter, $update, $options = []): array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            // Find documents that match the filter
            $cursor = $this->collection->find($filter);

            // Update matching documents
            $this->collection->updateMany($filter, $update, $options);

            // Retrieve IDs of updated documents
            $updatedDocumentIds = [];
            foreach ($cursor as $document) {
                $updatedDocumentIds[] = (string) $document['_id'];
            }

            return $updatedDocumentIds;
        } catch (\Throwable $e) {
            throw new Exception("UpdateMany operation failed: " . $e->getMessage());
        }
    }


    public function deleteOne($filter, $options = []): void
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $this->collection->deleteOne($filter, $options);
        } catch (\Throwable $e) {
            throw new Exception("DeleteOne operation failed: " . $e->getMessage());
        }
    }

    public function deleteMany($filter, $options = []): array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            // Find documents that match the filter before deletion
            $cursor = $this->collection->find($filter);

            // Delete matching documents
            $deleteResult = $this->collection->deleteMany($filter, $options);

            // Retrieve IDs of deleted documents
            $deletedDocumentIds = [];
            foreach ($cursor as $document) {
                $deletedDocumentIds[] = (string) $document['_id'];
            }

            return $deletedDocumentIds;
        } catch (\Throwable $e) {
            throw new Exception("DeleteMany operation failed: " . $e->getMessage());
        }
    }

    public function aggregate($pipeline = [], $options = []): array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $cursor = $this->collection->aggregate($pipeline, $options);
            return iterator_to_array($cursor);
        } catch (\Throwable $e) {
            throw new Exception("Aggregate operation failed: " . $e->getMessage());
        }
    }

    public function createIndex($keys, $options = []): void
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $this->collection->createIndex($keys, $options);
        } catch (\Throwable $e) {
            throw new Exception("CreateIndex operation failed: " . $e->getMessage());
        }
    }

    public function dropIndex($indexName): void
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            $this->collection->dropIndex($indexName);
        } catch (\Throwable $e) {
            throw new Exception("DropIndex operation failed: " . $e->getMessage());
        }
    }

    public function storeFile($filename, $options = []): void
    {
        $bucket = $this->db->selectGridFSBucket();
        $stream = fopen($filename, 'r');
        $bucket->uploadFromStream(basename($filename), $stream, $options);
    }

    public function getFile($filename): ?string
    {
        $bucket = $this->db->selectGridFSBucket();
        $downloadStream = $bucket->openDownloadStreamByName($filename);
        return stream_get_contents($downloadStream);
    }

    // Count Documents
    public function countDocuments($filter = [], $options = []): int
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            return $this->collection->countDocuments($filter, $options);
        } catch (\Throwable $e) {
            throw new Exception("CountDocuments operation failed: " . $e->getMessage());
        }
    }

    // Distinct Values
    public function distinctValues($field, $filter = [], $options = []): array
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            return $this->collection->distinct($field, $filter, $options);
        } catch (\Throwable $e) {
            throw new Exception("Distinct operation failed: " . $e->getMessage());
        }
    }

    // List Indexes
    public function listIndexes(): IndexInfoIterator
    {
        if (!$this->collection) {
            throw new Exception("No collection selected.");
        }

        try {
            return $this->collection->listIndexes();
        } catch (\Throwable $e) {
            throw new Exception("ListIndexes operation failed: " . $e->getMessage());
        }
    }

    // Start Session
    public function startSession()
    {
        try {
            return $this->client->startSession();
        } catch (\Throwable $e) {
            throw new Exception("StartSession operation failed: " . $e->getMessage());
        }
    }

}
