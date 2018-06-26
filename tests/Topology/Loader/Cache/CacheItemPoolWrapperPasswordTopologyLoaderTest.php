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

namespace NessTest\Component\Password\Topology\Loader\Cache;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Ness\Component\Password\Topology\Loader\Cache\CacheItemPoolWrapperPasswordTopologyLoader;
use Cache\TagInterop\TaggableCacheItemPoolInterface;
use Cache\TagInterop\TaggableCacheItemInterface;

/**
 * CacheItemPoolWrapperPasswordTopologyLoader testcase
 * 
 * @see \Ness\Component\Password\Topology\Loader\Cache\CacheItemPoolWrapperPasswordTopologyLoader
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheItemPoolWrapperPasswordTopologyLoaderTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheItemPoolWrapperPasswordTopologyLoader::load()
     */
    public function testLoadWithPsr6(): void
    {
        $collection = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $item = $this->getMockBuilder(CacheItemInterface::class)->getMock();
        $pool = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $loader->expects($this->once())->method("load")->with(3, "Foo")->will($this->returnValue($collection));
        
        $item->expects($this->exactly(2))->method("isHit")->will($this->onConsecutiveCalls(false, true));
        $item->expects($this->once())->method("set")->with($collection);
        $item->expects($this->once())->method("get")->will($this->returnValue($collection));
        
        $pool->expects($this->exactly(2))->method("getItem")->withConsecutive(["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3"])->will($this->returnValue($item));
        
        $wrapper = new CacheItemPoolWrapperPasswordTopologyLoader($pool, $loader);
        
        $this->assertSame($collection, $wrapper->load(3, "Foo"));
        $this->assertSame($collection, $wrapper->load(3, "Foo"));
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheItemPoolWrapperPasswordTopologyLoader::load()
     */
    public function testLoadWithPsr6Taggable(): void
    {
        $collection = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $item = $this->getMockBuilder(TaggableCacheItemInterface::class)->getMock();
        $pool = $this->getMockBuilder(TaggableCacheItemPoolInterface::class)->getMock();
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $loader->expects($this->once())->method("load")->with(3, "Foo")->will($this->returnValue($collection));
        
        $item->expects($this->exactly(2))->method("isHit")->will($this->onConsecutiveCalls(false, true));
        $item->expects($this->once())->method("set")->with($collection);
        $item->expects($this->once())->method("setTags")->with(["PASSWORD_TOPOLOGY", "PASSWORD_TOPOLOGY_Foo"]);
        $item->expects($this->once())->method("get")->will($this->returnValue($collection));
        
        $pool->expects($this->exactly(2))->method("getItem")->withConsecutive(["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3"])->will($this->returnValue($item));
        
        $wrapper = new CacheItemPoolWrapperPasswordTopologyLoader($pool, $loader);
        
        $this->assertSame($collection, $wrapper->load(3, "Foo"));
        $this->assertSame($collection, $wrapper->load(3, "Foo"));
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheItemPoolWrapperPasswordTopologyLoader::invalidate()
     */
    public function testInvalidateWithPsr6(): void
    {
        $collection = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $item = $this->getMockBuilder(CacheItemInterface::class)->getMock();
        $pool = $this->getMockBuilder(CacheItemPoolInterface::class)->getMock();
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $loader->expects($this->never())->method("load");
        $item->expects($this->exactly(6))->method("isHit")->will($this->returnValue(true));
        $item->expects($this->exactly(6))->method("get")->will($this->returnValue($collection));
        $pool->expects($this->exactly(6))->method("getItem")->withConsecutive(
            [ "CACHE_TOPOLOGY_LOADER_Foo_LIMIT_" ],
            [ "CACHE_TOPOLOGY_LOADER_Bar_LIMIT_3" ],
            [ "CACHE_TOPOLOGY_LOADER_Foo_LIMIT_5" ]
        )->will($this->returnValue($item));
        $pool->expects($this->exactly(3))->method("deleteItems")->withConsecutive(
            [ ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_", "CACHE_TOPOLOGY_LOADER_Foo_LIMIT_5", "CACHE_TOPOLOGY_LOADER_Bar_LIMIT_3"] ],
            [ ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_", "CACHE_TOPOLOGY_LOADER_Foo_LIMIT_5"] ],
            [ ["CACHE_TOPOLOGY_LOADER_Bar_LIMIT_3"] ]
        )->will($this->returnValue(true));
        
        $wrapper = new CacheItemPoolWrapperPasswordTopologyLoader($pool, $loader);
        
        $this->assertTrue($wrapper->invalidate(null));
        $this->assertTrue($wrapper->invalidate("Foo"));
        
        $wrapper->load(null, "Foo");
        $wrapper->load(3, "Bar");
        $wrapper->load(5, "Foo");
        
        $this->assertTrue($wrapper->invalidate(null));
        
        $wrapper->load(null, "Foo");
        $wrapper->load(3, "Bar");
        $wrapper->load(5, "Foo");
        
        $this->assertTrue($wrapper->invalidate("Foo"));
        $this->assertTrue($wrapper->invalidate("Bar"));
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheItemPoolWrapperPasswordTopologyLoader::invalidate()
     */
    public function testInvalidateWithPsr6Taggable(): void
    {
        $pool = $this->getMockBuilder(TaggableCacheItemPoolInterface::class)->getMock();
        $pool->expects($this->exactly(2))->method("invalidateTag")->withConsecutive(["PASSWORD_TOPOLOGY"], ["PASSWORD_TOPOLOGY_Foo"])->will($this->returnValue(true));
        
        $wrapper = new CacheItemPoolWrapperPasswordTopologyLoader($pool, $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock());
        
        $this->assertTrue($wrapper->invalidate(null));
        $this->assertTrue($wrapper->invalidate("Foo"));
    }
    
}
