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
use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader;

/**
 * NativePasswordTopologyLoader testcase
 * 
 * @see \Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyLoaderTest extends TestCase
{
    
    /**
     * Topologies given to the loader
     * 
     * @var array
     */
    private $topologiesMap = [
        "BarIdentifier"     =>  [
            "foo", "bar", "moz"
        ],
        "PozIdentifier"     =>  []
    ];
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader::load()
     */
    public function testLoadWhenFound(): void
    {       
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->exactly(2))->method("generatedBy")->will($this->returnValue("BarIdentifier"));
        
        $loader = new NativePasswordTopologyLoader($this->topologiesMap);
        
        // with limit
        $loadedLimited = $loader->load($topologyGiven, 2);
        // with no limit
        $loadedFull = $loader->load($topologyGiven, null);
        
        $this->assertSame(2, \count($loadedLimited));
        $this->assertSame("foo", $loadedLimited[0]->getTopology());
        $this->assertSame("bar", $loadedLimited[1]->getTopology());
        
        $this->assertSame(3, \count($loadedFull));
        $this->assertSame("moz", $loadedFull[2]->getTopology());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader::load()
     */
    public function testLoadWhenNotFound(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->once())->method("generatedBy")->will($this->returnValue("FooIdentifier"));
        
        $loader = new NativePasswordTopologyLoader($this->topologiesMap);
        
        $this->assertNull($loader->load($topologyGiven, 42));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader::load()
     */
    public function testLoadWhenNoIdentifierCorresponding(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->once())->method("generatedBy")->will($this->returnValue("PozIdentifier"));
        
        $loader = new NativePasswordTopologyLoader($this->topologiesMap);
        
        $this->assertNull($loader->load($topologyGiven, 42));
    }
    
}
