<?php

namespace Naroga\SearchBundle\Search;

use Naroga\SearchBundle\Engine\EngineInterface;
use Naroga\SearchBundle\Exception\FileNotFoundException;

/**
 * Class Search
 * @package Naroga\SearchBundle\Search
 */
class Search
{
    /** @var EngineInterface */
    private $engine;

    public function __construct(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Adds a new file.
     *
     * @param string $path
     * @throws FileNotFoundException
     */
    public function add(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException("File '$path' not found'");
        }

        return $this->engine->add($path, file_get_contents($path));
    }

    /**
     * Searches for a file.
     *
     * @param string $expression
     * @return array
     */
    public function search(string $expression) : array
    {
        return $this->engine->search($expression);
    }

    /**
     * Removes an entry by its ID.
     *
     * @param string $id
     */
    public function delete(string $id)
    {
        return $this->engine->delete($id);
    }
}
