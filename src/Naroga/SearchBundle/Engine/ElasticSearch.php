<?php

namespace Naroga\SearchBundle\Engine;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use Naroga\SearchBundle\Entity\File;

/**
 * Class ElasticSearch
 * @package Naroga\SearchBundle\Engine
 */
class ElasticSearch implements EngineInterface
{
    private $host;
    private $indexName;
    private $serializer;
    private $client;

    public function __construct($host, $indexName, $serializer, ClientInterface $client)
    {
        $this->host = $host;
        $this->indexName = $indexName;
        $this->serializer = $serializer;
        $this->client = $client;
    }

    public function add(string $name, string $content)
    {
        $result = $this->client->post('/' . $this->indexName . '/external/?pretty', [
            'body' => $this->serializer->serialize(new File($name, $content), 'json')
        ]);

        $deserializedResult = $this->serializer->deserialize($result->getBody()->getContents(), 'array', 'json');
        return $deserializedResult["created"] ? $deserializedResult['_id'] : false;
    }

    public function search(string $expression) : array
    {
        $response = $this->client->post('/' . $this->indexName . '/_search?pretty', [
            'body' => $this->serializer->serialize([
                'query' => [
                    'match' => [
                        'content' => $expression
                    ]
                ]
            ], 'json')
        ]);

        $result = [];

        $deserializedResponse = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
        foreach ($deserializedResponse['hits']['hits'] as $hit) {
            $file = new File($hit['_source']['name'], $hit['_source']['content']);
            $result[] = ['score' => $hit['_score'], 'file' => $file];
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id)
    {
        try {
            $response = $this->client->delete('/' . $this->indexName . '/external/' . $id . '?pretty');
        } catch (ClientException $e) {
            return false;
        }
        $deserializedResponse = $this->serializer->deserialize($response->getBody()->getContents(), 'array', 'json');
        return $deserializedResponse['found'] ? $deserializedResponse['_shards']['successful'] : false;
    }

}
