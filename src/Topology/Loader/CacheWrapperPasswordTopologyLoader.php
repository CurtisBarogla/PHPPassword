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
use Ness\Component\Password\Traits\HelperTrait;

/**
 * Simple wrapper around a setted PasswordTopologyLoader to cache and re-use collection already loaded
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheWrapperPasswordTopologyLoader implements CacheablePasswordTopologyLoaderInterface
{
    
    use HelperTrait;
    
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
     * Fetched keys from the cache
     * 
     * @var string[]
     */
    private $fetched;
    
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
        $key = $this->interpolate(self::CACHE_KEY_PATTERN, ["generator" => $generator, "limit" => $limit]);
        if(null === $collection = $this->cache->get($key, null)) {
            $collection = $this->loader->load($limit, $generator);
            
            $this->cache->set($key, $collection);
            
            return $collection;
        } else {
            $this->fetched[$generator][] = $key;
            
            return $collection;
        }
    }
    
    /**
     * This implementation can only invalidate collection fetched from a previous call to load
     * 
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\CacheablePasswordTopologyLoaderInterface::invalidate()
     */
    public function invalidate(?string $generator): bool
    {
        if(null === $this->fetched || null !== $generator && !isset($this->fetched[$generator]))
            return true;
        
        $toInvalidate = [];
        $toInvalidate = (null === $generator) ? \array_merge_recursive($toInvalidate, ...\array_values($this->fetched)) : $this->fetched[$generator];
        
        $invalidation = $this->cache->deleteMultiple($toInvalidate);
        if(null !== $generator)
            unset($this->fetched[$generator]);
        else
            $this->fetched = null;
        
        return $invalidation;
    }
    
}
