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

namespace Ness\Component\Password\Topology\Loader;

use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Psr\SimpleCache\CacheInterface;

/**
 * Simple wrapper around a setted PasswordTopologyLoader to cache and re-use collection already loaded
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheWrapperPasswordTopologyLoader implements PasswordTopologyLoaderInterface
{
    
    /**
     * PSR-16 Cache implementation
     * 
     * @var CacheInterface
     */
    private $cache;
    
    /**
     * Password topology loader to wrap
     * 
     * @var PasswordTopologyLoaderInterface
     */
    private $loader;
    
    /**
     * Initialize loader
     * 
     * @param CacheInterface $cache
     *   PSR-16 Cache implementation
     * @param PasswordTopologyLoaderInterface $loader
     *   Loader to wrap
     */
    public function __construct(CacheInterface $cache, PasswordTopologyLoaderInterface $loader)
    {
        $this->cache = $cache;
        $this->loader = $loader;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(?int $limit, string $generator): PasswordTopologyCollection
    {
        $key = "CACHE_TOPOLOGY_LOADER_{$generator}_LIMIT_{$limit}";
        if(null === $collection = $this->cache->get($key, null)) {
            $collection = $this->loader->load($limit, $generator);
            
            $this->cache->set($key, $collection);
            
            return $collection;
        } else {
            return $collection;
        }
    }
    
}
