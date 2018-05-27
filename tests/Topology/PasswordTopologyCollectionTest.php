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

namespace NessTest\Component\Password\Topology;

use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Ness\Component\Password\Topology\PasswordTopology;

/**
 * PasswordTopologyCollection testcase
 * 
 * @see \Ness\Component\Password\Topology\PasswordTopologyCollection
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyCollectionTest extends PasswordTopologyTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopologyCollection::add()
     */
    public function testAdd(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(10))->method("generatedBy")->will($this->returnValue("Foo"));
        $topology->expects($this->exactly(13))->method("get")->will($this->returnValue("Foo"));
        
        $nonCompatibleTopology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $nonCompatibleTopology->expects($this->exactly(2))->method("generatedBy")->will($this->returnValue("Bar"));
        $nonCompatibleTopology->expects($this->never())->method("get");
                
        $collection = new PasswordTopologyCollection();
        
        $this->assertNull($collection->add($topology));
        $this->assertNull($collection->add($nonCompatibleTopology));
        
        $topologies = $this->extractTopologies($collection);
        
        // check initialized at 1
        $this->assertSame(1, $topologies["Foo"]);
        
        $this->assertNull($collection->add($topology));
        
        $topologies = $this->extractTopologies($collection);
        
        // check basic incrementation
        $this->assertSame(2, $topologies["Foo"]);
        
        $this->assertNull($collection->add($topology, 20));
        
        $topologies = $this->extractTopologies($collection);
        
        // check value added
        $this->assertSame(22, $topologies["Foo"]);
        
        $this->assertNull($collection->add($topology, null));
        
        $topologies = $this->extractTopologies($collection);
        
        // set to null
        $this->assertSame(null, $topologies["Foo"]);
        
        $this->assertNull($collection->add($topology, 11));
        
        $topologies = $this->extractTopologies($collection);
        
        // check that null topology cannot be overriden
        $this->assertSame(null, $topologies["Foo"]);
    }
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopologyCollection::has()
     */
    public function testHas(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(2))->method("get")->will($this->onConsecutiveCalls("Foo", "Bar"));
        $topology->expects($this->exactly(3))->method("generatedBy")->will($this->onConsecutiveCalls("Foo", "Foo", "Bar"));
        
        $collection = new PasswordTopologyCollection();
        $this->injectForTest($collection, "Foo", ["Foo" => 1]);
        
        $this->assertTrue($collection->has($topology));
        $this->assertFalse($collection->has($topology));
        $this->assertFalse($collection->has($topology));
    }
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopologyCollection::extract()
     */
    public function testExtract(): void
    {
        $collection = new PasswordTopologyCollection();
        $this->injectForTest($collection, "Foo", ["Foo" => null, "Bar" => 1, "Moz" => 2], 1);
        
        $extract = $collection->extract(1);
        $topologies = $this->extractTopologies($extract);
        
        $this->assertSame(["Foo" => null, "Moz" => 2], $topologies);
        
        $collection = new PasswordTopologyCollection();
        $this->injectForTest($collection, "Foo", ["Foo" => null, "Bar" => 1], 1);
        
        $extract = $collection->extract(1, false);
        $topologies = $this->extractTopologies($extract);
        
        $this->assertSame(["Foo" => null], $topologies);
    }
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopologyCollection::merge()
     */
    public function testMerge(): void
    {
        $collectionOne = new PasswordTopologyCollection();
        $this->injectForTest($collectionOne, "Foo", ["Foo" => null, "Bar" => 1, "Moz" => null], 2);
        
        $base = $collection = new PasswordTopologyCollection();
        $this->injectForTest($collection, "Foo", ["Bar" => 3, "Moz" => 3], 0);
        
        $collection->merge($collectionOne);
        
        $topologies = $this->extractTopologies($collection);
        
        $this->assertSame(["Moz" => null, "Foo" => null, "Bar" => 4], $topologies);
        
        $collectionIncompatible = new PasswordTopologyCollection();
        $this->injectForTest($collectionIncompatible, "Bar", ["Foo" => null, "Bar" => 1, "Moz" => null], 2);
        
        $this->assertSame($base->merge($collectionIncompatible), $base);
    }
    
}
