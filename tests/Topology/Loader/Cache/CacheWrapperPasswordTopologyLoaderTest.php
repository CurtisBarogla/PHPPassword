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

namespace NessTest\Component\Password\Topology\Loader;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Psr\SimpleCache\CacheInterface;
use Ness\Component\Password\Topology\Loader\Cache\CacheWrapperPasswordTopologyLoader;

/**
 * CacheWrapperPasswordTopologyLoader testcase
 * 
 * @see \Ness\Component\Password\Topology\Loader\CacheWrapperPasswordTopologyLoader
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CacheWrapperPasswordTopologyLoaderTest extends PasswordTestCase
{
    
    /**
     * {@inheritDoc}
     * @see \PHPUnit\Framework\TestCase::setUpBeforeClass()
     */
    public static function setUpBeforeClass(): void
    {
        if(!\interface_exists("Psr\SimpleCache\CacheInterface"))
            $this->markTestSkipped("PSR16 not found");
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheWrapperPasswordTopologyLoader::load()
     */
    public function testLoad(): void
    {
        $collection = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->exactly(2))->method("load")->withConsecutive([null, "Foo"], [3, "Foo"])->will($this->returnValue($collection));
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->exactly(3))->method("get")->withConsecutive(
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_"], 
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3"], 
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3"])
        ->will($this->onConsecutiveCalls(null, null, $collection));
        $cache->expects($this->exactly(2))->method("set")->withConsecutive(
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_", $collection],
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3", $collection])
        ->will($this->returnValue(true));
        
        $wrapper = new CacheWrapperPasswordTopologyLoader($cache, $loader);
        
        $this->assertSame($collection, $wrapper->load(null, "Foo"));
        $this->assertSame($collection, $wrapper->load(3, "Foo"));
        $this->assertSame($collection, $wrapper->load(3, "Foo"));
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\Cache\CacheWrapperPasswordTopologyLoader::invalidate()
     */
    public function testInvalidate(): void
    {
        $collection = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $cache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $cache->expects($this->exactly(6))->method("get")->withConsecutive(
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_"],
            ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3"],
            ["CACHE_TOPOLOGY_LOADER_Bar_LIMIT_5"]
        )->will($this->returnValue($collection));
        $cache->expects($this->exactly(3))->method("deleteMultiple")->withConsecutive(
            [ ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_", "CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3", "CACHE_TOPOLOGY_LOADER_Bar_LIMIT_5"] ],
            [ ["CACHE_TOPOLOGY_LOADER_Foo_LIMIT_", "CACHE_TOPOLOGY_LOADER_Foo_LIMIT_3"] ],
            [ ["CACHE_TOPOLOGY_LOADER_Bar_LIMIT_5"] ]
        )->will($this->returnValue(true));
        
        $wrapper = new CacheWrapperPasswordTopologyLoader($cache, $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock());
        
        $this->assertTrue($wrapper->invalidate(null));
        $this->assertTrue($wrapper->invalidate("Foo"));
        
        $wrapper->load(null, "Foo");
        $wrapper->load(3, "Foo");
        $wrapper->load(5, "Bar");
        
        $this->assertTrue($wrapper->invalidate(null));
        
        $wrapper->load(null, "Foo");
        $wrapper->load(3, "Foo");
        $wrapper->load(5, "Bar");
        
        $this->assertTrue($wrapper->invalidate("Foo"));
        $this->assertTrue($wrapper->invalidate("Bar"));
    }
    
}
