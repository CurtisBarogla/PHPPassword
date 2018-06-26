<?php
//StrictType
declare(strict_types = 1);

/*
 * Ness
 * Password component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace Ness\Component\Password\Topology\Loader\Cache;

/**
 * Provide fetches container for setting fetched generator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class AbstractCacheablePasswordTopologyLoader implements CacheablePasswordTopologyLoaderInterface
{
    
    /**
     * All fetched collection from a cache component
     * 
     * @var string[]
     */
    protected $fetched;
    
    /**
     * Set a fetched collection key from a generator
     * 
     * @param string $generator
     *   Generator name
     * @param string $key
     *   Key to set
     */
    protected function setFetched(string $generator, string $key): void
    {
        $this->fetched[$generator][] = $key;
    }
    
    /**
     * Get lasted fetched cache keys from a cache component.
     * Will return null if no cache keys has been found for the given generator or no values has been setted
     * 
     * @param string|null $generator
     *   Generator to get the keys or null the get all fetched keys
     * 
     * @return array|null
     *   All keys or null if no keys found
     */
    protected function getFetched(?string $generator): ?array
    {
        if(null === $this->fetched || null !== $generator && !isset($this->fetched[$generator]))
            return null;
        
        $keys = [];
        $keys = (null === $generator) ? \array_merge_recursive($keys, ...\array_values($this->fetched)) : $this->fetched[$generator];
        
        if(null === $generator)
            $this->fetched = null;
        else
            unset($this->fetched[$generator]);
        
        return $keys;
    }
    
}
