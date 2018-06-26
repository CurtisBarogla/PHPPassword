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
use Psr\Cache\CacheItemPoolInterface;
use Cache\TagInterop\TaggableCacheItemPoolInterface;
use Ness\Component\Password\Traits\HelperTrait;
use Cache\TagInterop\TaggableCacheItemInterface;

/**
 * Simple wrapper around a setted PasswordTopologyLoader to cache and re-use collection already loaded over a PSR6 cache implementation.
 * Support TaggableCachePool
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheItemPoolWrapperPasswordTopologyLoader extends AbstractCacheablePasswordTopologyLoader
{
    
    use HelperTrait;
    
    /**
     * PSR6 Cache component
     * 
     * @var TaggableCacheItemPoolInterface|CacheItemPoolInterface
     */
    private $pool;
    
    /**
     * Password topology loader to wrap
     * 
     * @var PasswordTopologyLoaderInterface
     */
    private $loader;
    
    /**
     * Initialize loader
     * 
     * @param CacheItemPoolInterface|TaggableCacheItemPoolInterface $pool
     *   Psr6 Cache implementation. Support TaggableCacheItemPoolInterface
     * @param PasswordTopologyLoaderInterface $loader
     *   Loader to wrap
     */
    public function __construct(CacheItemPoolInterface $pool, PasswordTopologyLoaderInterface $loader)
    {
        $this->pool = $pool;
        $this->loader = $loader;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(?int $limit, string $generator): PasswordTopologyCollection
    {
        $key = $this->interpolate(self::CACHE_KEY_PATTERN, ["generator" => $generator, "limit" => $limit]);
        if( !($item = $this->pool->getItem($key))->isHit() ) {
            $collection = $this->loader->load($limit, $generator); 
            
            if($item instanceof TaggableCacheItemInterface)
                $item->setTags(["PASSWORD_TOPOLOGY", "PASSWORD_TOPOLOGY_{$generator}"]);
            
            $item->set($collection);
            
            $this->pool->save($item);
            
            return $collection;
        }
        
        if(!$item instanceof TaggableCacheItemInterface)
            $this->setFetched($generator, $key);
        
        return $item->get();
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheablePasswordTopologyLoaderInterface::invalidate()
     */
    public function invalidate(?string $generator): bool
    {
        if($this->pool instanceof TaggableCacheItemPoolInterface) {
            return (null === $generator) 
                ? $this->pool->invalidateTag("PASSWORD_TOPOLOGY") 
                : $this->pool->invalidateTag("PASSWORD_TOPOLOGY_{$generator}");
        }
        
        return (null !== $keys = $this->getFetched($generator)) ? $this->pool->deleteItems($keys) : true;
    }
    
}
