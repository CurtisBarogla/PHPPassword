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

namespace ZoeTest\Component\Password\Topology;

use PHPUnit\Framework\TestCase;
use ZoeTest\Component\Password\Common\TopologyShortcut;
use Zoe\Component\Internal\GeneratorTrait;
use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Topology\PasswordTopologyCollection;

/**
 * PasswordTopologyCollection testcase
 * 
 * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyCollectionTest extends TestCase
{
    
    use GeneratorTrait;
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::getIterator()
     */
    public function testGetIterator(): void
    {
        $collection = new PasswordTopologyCollection();

        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz"]);
        
        $loop = 0; 
        foreach ($collection as $topology) {
            $this->assertInstanceOf(PasswordTopology::class, $topology);
            switch ($loop) {
                case 0:
                    $this->assertSame("foo", $topology->getTopology());
                    break;
                case 1:
                    $this->assertSame("bar", $topology->getTopology());
                    break;
                case 2:
                    $this->assertSame("moz", $topology->getTopology());
                    break;
            }
            $loop++;
        }
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::offsetExists()
     */
    public function testOffsetExists(): void
    {
        $collection = new PasswordTopologyCollection();

        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz"]);
        
        $this->assertTrue(isset($collection["foo"]));
        $this->assertFalse(isset($collection["poz"]));
        
        $this->assertTrue(isset($collection[1]));
        $this->assertFalse(isset($collection[3]));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::offsetGet()
     */
    public function testOffsetGet(): void
    {
        $collection = new PasswordTopologyCollection();
        
        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz"]);
        
        $topologies = [$collection["foo"], $collection[0]];
        
        foreach ($topologies as $topology) {
            $this->assertSame("foo", $topology->getTopology());
            $this->assertSame("FooGenerator", $topology->generatedBy());
        }
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::offsetSet()
     */
    public function testOffsetSet(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(2))->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topology->expects($this->exactly(1))->method("getTopology")->will($this->returnValue("foo"));
        
        $collection = new PasswordTopologyCollection();
        
        $collection[] = $topology;
        $collection[] = $topology;
        
        $this->assertSame(1, \count($collection));
        $this->assertTrue(isset($collection["foo"]));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::offsetUnset()
     */
    public function testOffsetUnset(): void
    {
        $collection = new PasswordTopologyCollection();
        
        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz"]);
        
        unset($collection[0]);
        
        $this->assertSame(2, \count($collection));
        $this->assertFalse(isset($collection["foo"]));
        
        unset($collection["bar"]);
        
        $this->assertSame(1, \count($collection));
        $this->assertFalse(isset($collection[1]));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::count()
     */
    public function testCount(): void
    {
        $collection = new PasswordTopologyCollection();
        
        $this->assertSame(0, \count($collection));
        
        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz"]);
        
        $this->assertSame(3, \count($collection));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::merge()
     */
    public function testMerge(): void
    {
        $topologies = TopologyShortcut::generateTopologies($this, ["FooGenerator" => ["foo", "bar", "moz"]])["FooGenerator"];
        $topologiesToMerge = $this->getGenerator($topologies);
        $collectionToMerge = $this->getMockBuilder(PasswordTopologyCollection::class)->getMock();
        $collectionToMerge->expects($this->once())->method("getIterator")->will($this->returnValue($topologiesToMerge));
        $collectionToMerge->expects($this->exactly(2))->method("getCollectionGeneratorIdentifier")->will($this->returnValue("FooGenerator"));
        
        $collection = new PasswordTopologyCollection();
        
        $this->assertSame(0, \count($collection));
        
        $this->assertNull($collection->merge($collectionToMerge));
        
        $this->assertSame(3, \count($collection));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::extract()
     */
    public function testExtract(): void
    {
        $collection = new PasswordTopologyCollection();
        
        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz", "poz"]);
        
        $new = $collection->extract(3);
        
        $this->assertSame(3, \count($new));
        $this->assertSame("FooGenerator", $new->getCollectionGeneratorIdentifier());
        $this->assertSame("foo", $new[0]->getTopology());
        $this->assertSame("bar", $new[1]->getTopology());
        $this->assertSame("moz", $new[2]->getTopology());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::getCollectionGeneratorIdentifier()
     */
    public function testGetCollectionGeneratorIdentifier(): void
    {
        $collection = new PasswordTopologyCollection();
        
        $this->insertFixtures($collection, "FooGenerator", ["foo", "bar", "moz"]);
        
        $this->assertSame("FooGenerator", $collection->getCollectionGeneratorIdentifier());
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::offsetSet()
     */
    public function testExceptionWhenNonPasswordTopologyIsGivenWhenSetting(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Value setted into a PasswordTopologyCollection MUST be an instance of PasswordTopology");
        
        $collection = new PasswordTopologyCollection();
        
        $collection[] = "foo";
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::offsetSet()
     */
    public function testExceptionWhenDifferentGeneratorIdentifierIsGivenFromPreviouslyRegisteredTopologies(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Cannot register this topology into this collection. Reserved to 'FooGenerator' generator identifier ; 'BarGenerator' generator given");
        
        $topology1 = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology1->expects($this->exactly(1))->method("generatedBy")->will($this->returnValue("FooGenerator"));
        
        $topology2 = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology2->expects($this->exactly(2))->method("generatedBy")->will($this->returnValue("BarGenerator"));
        
        $collection = new PasswordTopologyCollection();
        
        $collection[] = $topology1;
        $collection[] = $topology2;
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::merge()
     */
    public function testExceptionMergeWhenGivenCollectionGeneratorIdentifierIsDifferentFromSettedOne(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Cannot merge collection composed of topologies generated with 'BarGenerator' generator with 'FooGenerator' generator");
        
        $collectionToMerge = $this->getMockBuilder(PasswordTopologyCollection::class)->getMock();
        $collectionToMerge->expects($this->exactly(2))->method("getCollectionGeneratorIdentifier")->will($this->returnValue("BarGenerator"));
        
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->once())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        
        $collection = new PasswordTopologyCollection();
        $collection[] = $topology;
        
        $collection->merge($collectionToMerge);
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopologyCollection::getCollectionGeneratorIdentifier()
     */
    public function testExceptionWhenTryingToGetACollectionGeneratorIdentifierWhenCollectionIsEmpty(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage("Cannot get a generator identifier from an empty PasswordTopologyCollection");
        
        $collection = new PasswordTopologyCollection();
        
        $collection->getCollectionGeneratorIdentifier();
    }
    
    /**
     * Insert mocked password topologies into a declared collection
     * 
     * @param PasswordTopologyCollection $collection
     *   Collection which mocked topology are inserted
     * @param string $generator
     *   Topology generator identifier
     * @param array $topologies
     *   Raw topologies to insert
     */
    private function insertFixtures(PasswordTopologyCollection $collection, string $generator, array $topologies): void
    {
        $topologies = TopologyShortcut::generateTopologies($this, [$generator => $topologies]);
        
        foreach ($topologies[$generator] as $topology) {
            $collection[] = $topology;
        }
    }
    
}
