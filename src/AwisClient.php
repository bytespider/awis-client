<?php

namespace Awis;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use Aws\Credentials\Credentials;
use Aws\Signature\SignatureV4;

class AwisClient
{
    protected $client;
    protected $region = 'us-west-1';
    protected $endPoint = 'https://awis.us-west-1.amazonaws.com';
    protected $endPointUri = '/api';

    public function __construct($key, $secret)
    {
        $credentials = new Credentials($key, $secret);
        $signature = new SignatureV4('awis', $this->region);

        $handler = new CurlHandler();

        $stack = HandlerStack::create($handler);
        $stack->push(function (callable $handler) use ($credentials, $signature) {
            return function (RequestInterface $request, array $options) use ($handler, $credentials, $signature) {
                $request = $signature->signRequest($request, $credentials);
                return $handler($request, $options);
            };
        });

        $this->client = new Client([
            'handler'  => $stack,
            'base_uri' => $this->endPoint
        ]);
    }

    public function getUrlInfo($url, $responseGroup = 'TrafficData')
    {
        return $this->makeRequest([
            'Action'        => 'UrlInfo',
            'Url'           => $url,
            'ResponseGroup' => $responseGroup,
        ]);
    }

    public function getTrafficHistory($url, $range = 31, $start = null)
    {
        return $this->makeRequest([
            'Action'        => 'TrafficHistory',
            'Url'           => $url,
            'ResponseGroup' => 'History',
            'Range'         => $range,
            'Start'         => $start,
        ]);
    }

    public function getCategoryBrowse($url, $responseGroup = 'Categories', $path, $descriptions = 'True')
    {
        return $this->makeRequest([
            'Action'        => 'CategoryBrowse',
            'Url'           => $url,
            'ResponseGroup' => $responseGroup,
            'Path'          => $path,
            'Descriptions'  => $descriptions,
        ]);
    }

    public function getCategoryListings($url, $path, $sortBy = 'Popularity', $recursive = 'False', $start = 1, $count = 20, $descriptions = 'True')
    {
        return $this->makeRequest([
            'Action'        => 'CategoryListings',
            'Url'           => $url,
            'ResponseGroup' => 'Listings',
            'Path'          => $path,
            'SortBy'        => $sortBy,
            'Recursive'     => $recursive,
            'Start'         => $start,
            'Count'         => $count,
            'Descriptions'  => $descriptions,
        ]);
    }

    public function getSitesLinkingIn($url, $count = 10, $start = 0)
    {
        return $this->makeRequest([
            'Action'        => 'SitesLinkingIn',
            'Url'           => $url,
            'ResponseGroup' => 'SitesLinkingIn',
            'Count'         => $count,
            'Start'         => $start,
        ]);
    }

    protected function makeRequest($query)
    {
        return $this->client->get($this->endPointUri, [
            'query' => $query
        ]);
    }
}