<?php

namespace Naroga\SearchBundle\Tests\Search;

use Naroga\SearchBundle\Engine\ElasticSearch;
use Naroga\SearchBundle\Engine\InMemory;
use Naroga\SearchBundle\Entity\File;
use Naroga\SearchBundle\Search\Search;

/**
 * Class SearchTest
 * @package Naroga\SearchBundle\Tests\Search
 */
class SearchTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd() {
        $esMock = $this->getMockBuilder(ElasticSearch::class)
            ->disableOriginalConstructor()
            ->getMock();
        $esMock->expects($this->any())->method('add')->will($this->returnValue(
            '123'
        ));
        $search = new Search($esMock);
        $this->assertEquals($search->add(__DIR__ . '/file.txt'), '123');
    }

    /**
     * @expectedException \Naroga\SearchBundle\Exception\FileNotFoundException
     */
    public function testAddFails() {
        $esMock = $this->getMockBuilder(ElasticSearch::class)
            ->disableOriginalConstructor()
            ->getMock();
        $esMock->expects($this->any())->method('add')->will($this->returnValue(
            '123'
        ));
        $search = new Search($esMock);
        $this->assertEquals($search->add(__DIR__ . '/fileDoesntExist.txt'), '123');
    }

    public function testSearch() {
        $esMock = $this->getMockBuilder(ElasticSearch::class)
            ->disableOriginalConstructor()
            ->getMock();
        $esMock->expects($this->any())->method('search')->will($this->returnValue(
            [['score' => 1, 'file' => new File('test.txt', 'this is a test file')]]
        ));
        $search = new Search($esMock);
        $this->assertEquals($search->search('test')[0]['score'], 1);
        $this->assertInstanceOf(File::class, $search->search('test')[0]['file']);
    }

    public function testDelete() {
        $esMock = $this->getMockBuilder(ElasticSearch::class)
            ->disableOriginalConstructor()
            ->getMock();
        $esMock->expects($this->any())->method('delete')->will($this->returnValue(
            1
        ));
        $search = new Search($esMock);
        $this->assertEquals($search->delete('123'), 1);
    }
}