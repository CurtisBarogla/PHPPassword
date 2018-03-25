<?php
//StrictType
declare(strict_types = 1);

/*
 * Zoe
 * Password component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace Zoe\Component\Password\Topology\Loader;

use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Topology\PasswordTopologyCollection;
use Psr\SimpleCache\CacheInterface;

/**
 * Wrap a PasswordTopologyLoader implementation around a PSR16 cache implementation
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
     * Loader wrapped
     * 
     * @var PasswordTopologyLoaderInterface
     */
    protected $loader;
    
    /**
     * Used to identify the cached value from the wrapped loader
     * 
     * @var string
     */
    const TOPOLOGY_LOADER_CACHE_KEY = "_PASSWORD_TOPOLOGY_LOADER_CACHE_";
    
    /**
     * Initialize wrapper
     * 
     * @param CacheInterface $cache
     *   PSR-16 cache implementation
     * @param PasswordTopologyLoaderInterface $loader
     *   PasswordTopologyLoader to wrap
     */
    public function __construct(CacheInterface $cache, PasswordTopologyLoaderInterface $loader)
    {
        $this->cache = $cache;
        $this->loader = $loader;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(PasswordTopology $topology, ?int $limit): ?PasswordTopologyCollection
    {
        if(false === $collection = $this->cache->get(self::TOPOLOGY_LOADER_CACHE_KEY, false)) {
             $collection = $this->loader->load($topology, $limit);
             
             $this->cache->set(self::TOPOLOGY_LOADER_CACHE_KEY, $collection);
             
             return $collection;
        } else {
            return $collection;
        }
    }
        
    /**
     * Invalidate a cached value from the wrapped loader
     * 
     * @return bool
     *   True if the cached value has been correctly invalidate. False otherwise
     */
    public function invalidate(): bool
    {
        return $this->cache->delete(self::TOPOLOGY_LOADER_CACHE_KEY);
    }
    
    /**
     * Get cache implementation linked to the wrapper
     * 
     * @return CacheInterface
     *   PSR-16 cache implementation
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

}
