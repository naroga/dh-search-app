<?php

namespace Naroga\SearchBundle\Engine;

use Naroga\SearchBundle\Entity\File;

/**
 * Interface SearchInterface
 * @package Naroga\SearchBundle\Search
 */
interface EngineInterface
{
    /**
     * Indexes a new entry on the search engine database.
     *
     * @param string $name The file name.
     * @param string $content The file content.
     */
    public function add(string $name, string $content);

    /**
     * An expression to be searched.
     *
     * @param string $expression The expression.
     * @return array The search result.
     */
    public function search(string $expression) : array;

    /**
     * Deletes an entry from the search engine database.
     *
     * @param string $id
     */
    public function delete(string $id);
}
