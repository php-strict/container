<?php
/**
 * PHP Strict.
 * 
 * @copyright   Copyright (C) 2018 - 2019 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

declare(strict_types=1);

namespace PhpStrict\Container;

/**
 * Describes the interface of a container that exposes methods to check existings, get and set its entries.
 */
interface ContainerInterface
{
    /**
     * Initialization of container.
     * 
     * @param array $items = [] 
     */
    public function __construct(array $items = []);
    
    /**
     * Returns true if the container can return an entry for the given key.
     * Returns false otherwise.
     * 
     * @param string $key Key of the entry to look for.
     * 
     * @return bool
     */
    public function has(string $key): bool;
    
    /**
     * Finds an entry of the container by its key and returns it.
     * 
     * @param string $key Key of the entry to look for.
     * 
     * @throws \PhpStrict\Container\NotFoundException  No entry was found for key.
     * @throws \PhpStrict\Container\ContainerException  Error while retrieving the entry.
     * 
     * @return mixed Entry
     */
    public function get(string $key);
    
    /**
     * Sets container entry with key and value.
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void;
    
    /**
     * Gets reference to container entry.
     * Usage: $obj =& $container->getRef('key');
     * 
     * @param string $key
     * @return mixed
     */
    public function &getRef(string $key);
    
    /**
     * Link container entry with reference.
     * 
     * @param string $key
     * @param mixed &$ref
     */
    public function setByRef(string $key, &$ref): void;
    
    /**
     * Returns all container entries.
     * 
     * @return array
     */
    public function getAll(): array;
}
