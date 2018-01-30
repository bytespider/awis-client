<?php

namespace Awis\Test;

use Awis\AwisClient;
use PHPUnit\Framework\TestCase;

class AwisClientTest extends TestCase
{
    protected $client;

    public function setUp()
    {
        $key          = getenv('IAM_ACCESS_KEY');
        $secret       = getenv('IAM_ACCESS_SECRET');

        $this->client = new AwisClient($key, $secret);
    }

    public function testGetUrlInfo()
    {
        $response = $this->client->getUrlInfo('yahoo.com');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetTrafficHistory()
    {
        $response = $this->client->getTrafficHistory('yahoo.com');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCategoryBrowse()
    {
        $response = $this->client->getCategoryBrowse('yahoo.com', 'Categories', 'Top/News', 'True');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetCategoryListings()
    {
        $response = $this->client->getCategoryListings('yahoo.com', 'Top/Arts');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetSitesLinkingIn()
    {
        $response = $this->client->getSitesLinkingIn('yahoo.com');
        $this->assertEquals(200, $response->getStatusCode());
    }
}