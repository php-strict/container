<?php
/**
 * PHP Strict.
 * 
 * @copyright   Copyright (C) 2018 - 2019 Enikeishik <enikeishik@gmail.com>. All rights reserved.
 * @author      Enikeishik <enikeishik@gmail.com>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace PhpStrict\Container;

/**
 * Container implementation, use own properties as entries.
 */
class Container implements ContainerInterface
{
    /**
     * Initialization of container.
     * 
     * @param array $items = null
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $val) {
            $this->$key = $val;
        }
    }
    
    /**
     * Returns true if the container can return an entry for the given key.
     * Returns false otherwise.
     *
     * @param string $key Key of the entry to look for.
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return property_exists($this, $key);
    }
    
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
    public function get(string $key)
    {
        if (!property_exists($this, $key)) {
            throw new NotFoundException();
        }
        return $this->$key;
    }
    
    /**
     * Sets container entry with key and value.
     * 
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $this->$key = $value;
    }
    
    /**
     * Gets reference to container entry.
     * Usage: $obj =& $container->getRef('key');
     * 
     * @param string $key
     * @return mixed
     */
    public function &getRef(string $key)
    {
        return $this->$key;
    }
    
    /**
     * Link container entry with reference.
     * 
     * @param string $key
     * @param mixed &$ref
     */
    public function setByRef(string $key, &$ref): void
    {
        $this->$key =& $ref;
    }
    
    /**
     * Returns all container entries.
     * 
     * @return array
     */
    public function getAll(): array
    {
        return get_object_vars($this);
    }
    
    /**
     * Passing all container entries into unpacker as associated array.
     * 
     * @param callable $unpacker
     */
    public function unpackWith(callable $unpacker): void
    {
        $unpacker($this->getAll());
    }
}
