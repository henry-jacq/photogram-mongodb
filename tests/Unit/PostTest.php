<?php

include __DIR__ . '/../../bootstrap.php';

use App\Model\Post;
use MongoDB\Client;
use App\Core\MongoDB;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    protected $db;
    protected $collectionName = 'posts';

    public function setUp(): void
    {
        parent::setUp(); // Always call parent setUp() method

        // Initialize MongoDB client and wrapper
        $client = new Client();
        $this->db = MongoDB::getInstance($client, 'photogram');

        // Set the collection to be used in tests
        $this->setCollection($this->collectionName);
    }

    public function tearDown(): void
    {
        // Clean up after each test if needed
        // For example, drop the collection to reset state
        // $this->db->dropCollection($this->collectionName);

        parent::tearDown(); // Always call parent tearDown() method
    }

    public function setCollection($collection)
    {
        $this->db->selectCollection($collection);
    }
    
    public function test_get_all_posts()
    {
        // Your test logic here
        var_dump($this->db->getCollection());

        // Add assertions to verify the correctness of your test
        // For example:
        // $this->assertNotEmpty($this->db->getCollection());
    }
}
