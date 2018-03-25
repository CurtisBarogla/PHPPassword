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
use Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection;
use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Topology\PasswordTopologyCollection;
use ZoeTest\Component\Password\Common\TopologyShortcut;
use Zoe\Component\Internal\GeneratorTrait;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * PasswordTopologyLoaderCollection testcase
 * 
 * @see \Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyLoaderCollectionTest extends TestCase
{
    
    use GeneratorTrait;
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection::addLoader()
     */
    public function testAddLoader(): void
    {
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $collection = new PasswordTopologyLoaderCollection([], "Foo", $loader);
        
        $this->assertNull($collection->addLoader("bar", $loader));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderCollection::load()
     */
    public function testLoad(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        
        $topologiesReturnedFromCollectionOne = TopologyShortcut::generateTopologies($this, ["" => ["foo", "bar", "moz"]])[""];
        $topologiesReturnedFromCollectionTwo = TopologyShortcut::generateTopologies($this, ["" => ["poz", "loz"]])[""];
        
        $loaderNull = $this->mockLoader(null, $topologyGiven, 7);
        $loaderFull = $this->mockLoader($this->mockCollection($topologiesReturnedFromCollectionOne), $topologyGiven, null);
        $loaderLimited = $this->mockLoader($this->mockCollection($topologiesReturnedFromCollectionTwo), $topologyGiven, 2);
        
        $collection = new PasswordTopologyLoaderCollection(["ALL" => -1, "NULL" => 7], "NULL", $loaderNull);
        $collection->addLoader("ALL", $loaderFull);
        $collection->addLoader("LIMITEDFALLBACK", $loaderLimited);
        
        $topologiesCollection = $collection->load($topologyGiven, 2);
        $this->assertCount(5, $topologiesCollection);
    }
    
    /**
     * Mock a Topology loader
     * 
     * @param PasswordTopologyCollection|null $collectionReturned
     *   Collection returned. Can be null
     * @param PasswordTopology $topology
     *   Topology passed to the loading process
     * @param int|null $limit
     *   Limit to apply
     * 
     * @return MockObject
     *   Mocked loader
     */
    private function mockLoader(?PasswordTopologyCollection $collectionReturned, PasswordTopology $topology, ?int $limit): MockObject
    {
        $mock = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $mock->expects($this->once())->method("load")->with($topology, $limit)->will($this->returnValue($collectionReturned));
        
        return $mock;
    }
    
    /**
     * Mock a collection of topologies returned by a loader
     * 
     * @param array $topologies
     *   Topology map
     * 
     * @return MockObject
     *   Mocked collection
     */
    private function mockCollection(array $topologies): MockObject
    {
        $mock = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method("getIterator")->will($this->returnValue($this->getGenerator($topologies)));
        
        return $mock;
    }
    
}
