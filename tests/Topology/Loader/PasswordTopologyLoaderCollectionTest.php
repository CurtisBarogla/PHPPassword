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

use NessTest\Component\Password\Topology\PasswordTopologyTestCase;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection;
use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Ness\Component\Password\Topology\PasswordTopology;

/**
 * PasswordTopologyLoaderCollection testcase
 * 
 * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyLoaderCollectionTest extends PasswordTopologyTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection::addLoader()
     */
    public function testAddLoader(): void
    {
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $collection = new PasswordTopologyLoaderCollection($loader);
        $this->assertNull($collection->addLoader($loader));
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection::load()
     */
    public function testLoad(): void
    {
        $topologyCollection = new PasswordTopologyCollection();
        $topologyCollection->add(new PasswordTopology("Foo", "Foo"), null);
        
        $topologyCollection2 = new PasswordTopologyCollection();
        $topologyCollection2->add(new PasswordTopology("Bar", "Foo"), 4);
        
        $default = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $default->expects($this->once())->method("load")->with(3, "Foo")->will($this->returnValue($topologyCollection));
        
        $added = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $added->expects($this->once())->method("load")->with(3, "Foo")->will($this->returnValue($topologyCollection2));
        
        $collection = new PasswordTopologyLoaderCollection($default);
        $collection->addLoader($added);
        $collection = $collection->load(3, "Foo");
        
        $topologies = $this->extractTopologies($collection);
        
        $this->assertSame($topologies["Foo"], null);
        $this->assertSame($topologies["Bar"], 4);
    }
    
}
