<?php

namespace Naroga\SearchBundle\Tests\Engine;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\Serializer;
use Naroga\SearchBundle\Engine\ElasticSearch;
use Naroga\SearchBundle\Entity\File;

/**
 * Class ElasticSearchTest
 * @package Naroga\SearchBundle\Tests\Engine
 */
class ElasticSearchTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode(['created' => true, '_id' => 123]))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $elasticSearch = new ElasticSearch(
            'localhost:9200',
            'file',
            \JMS\Serializer\SerializerBuilder::create()->build(),
            $client
        );

        $this->assertEquals($elasticSearch->add('test', 'this is a test'), '123');
    }

    public function testSearch()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode([
                'hits' => [
                    'hits' => [
                        0 => [
                            '_score' => 1,
                            '_source' => [
                                'name' => 'test.txt',
                                'content' => 'my content'
                            ]
                        ]
                    ]
                ]
            ]))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $elasticSearch = new ElasticSearch(
            'localhost:9200',
            'file',
            \JMS\Serializer\SerializerBuilder::create()->build(),
            $client
        );

        $result = $elasticSearch->search('content');
        $this->assertEquals($result[0]['score'], 1);
        $this->assertInstanceOf(File::class, $result[0]['file']);
        $this->assertEquals($result[0]['file']->getName(), 'test.txt');
    }

    public function testDelete()
    {
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], json_encode([
                'found' => true,
                '_shards' => [
                    'successful' => 1
                ]
            ]))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $elasticSearch = new ElasticSearch(
            'localhost:9200',
            'file',
            \JMS\Serializer\SerializerBuilder::create()->build(),
            $client
        );

        $result = $elasticSearch->delete('123');
        $this->assertEquals($result, 1);
    }
}
