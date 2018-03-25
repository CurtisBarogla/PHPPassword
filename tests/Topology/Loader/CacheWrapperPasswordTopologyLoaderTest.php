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

namespace ZoeTest\Component\Password\Topology\Loader;

use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Zoe\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader;
use Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * CacheWrapperPasswordTopologyLoader testcase
 * 
 * @see \Zoe\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheWrapperPasswordTopologyLoaderTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader::load()
     */
    public function testLoadFromCache(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->once())->method("get")->with(CacheWrapperPasswordTopologyLoader::TOPOLOGY_LOADER_CACHE_KEY, false)->will($this->returnValue(null));
        $cache->expects($this->never())->method("set");
        
        $loaderWrapped = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $wrapper = new CacheWrapperPasswordTopologyLoader($cache, $loaderWrapped);
        
        $this->assertNull($wrapper->load($topology, null));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader::load()
     */
    public function testLoadWhenNotCached(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->once())->method("get")->with(CacheWrapperPasswordTopologyLoader::TOPOLOGY_LOADER_CACHE_KEY, false)->will($this->returnValue(false));
        $cache->expects($this->once())->method("set")->with(CacheWrapperPasswordTopologyLoader::TOPOLOGY_LOADER_CACHE_KEY, null)->will($this->returnValue(true));
        
        $loaderWrapped = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loaderWrapped->expects($this->once())->method("load")->with($topology, 42)->will($this->returnValue(null));
        
        $wrapper = new CacheWrapperPasswordTopologyLoader($cache, $loaderWrapped);
        
        $this->assertNull($wrapper->load($topology, 42));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader::invalidate()
     */
    public function testInvalidate(): void
    {
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->once())->method("delete")->with(CacheWrapperPasswordTopologyLoader::TOPOLOGY_LOADER_CACHE_KEY)->will($this->returnValue(true));
        $loaderWrapped = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $wrapper = new CacheWrapperPasswordTopologyLoader($cache, $loaderWrapped);
        
        $this->assertTrue($wrapper->invalidate());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader::getCache()
     */
    public function testGetCache(): void
    {
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $loaderWrapped = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $wrapper = new CacheWrapperPasswordTopologyLoader($cache, $loaderWrapped);
        
        $this->assertSame($cache, $wrapper->getCache());
    }
    
}
