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

use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Psr\SimpleCache\CacheInterface;
use function Ness\Component\Password\interpolate;

/**
 * Simple wrapper around a setted PasswordTopologyLoader to cache and re-use collection already loaded
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheWrapperPasswordTopologyLoader extends AbstractCacheablePasswordTopologyLoader
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
        $key = interpolate(self::CACHE_KEY_PATTERN, ["generator" => $generator, "limit" => $limit]);
        if(null === $collection = $this->cache->get($key)) {
            $collection = $this->loader->load($limit, $generator);
            
            $this->cache->set($key, $collection);
            
            return $collection;
        }
        
        $this->setFetched($generator, $key);
        
        return $collection;
    }
    
    /**
     * This implementation can only invalidate collection fetched from a previous call to load
     * 
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheablePasswordTopologyLoaderInterface::invalidate()
     */
    public function invalidate(?string $generator): bool
    {
        return (null !== $keys = $this->getFetched($generator)) ? $this->cache->deleteMultiple($keys) : true;
    }
    
}
