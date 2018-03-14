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
use Zoe\Component\Password\Password;
use Zoe\Component\Password\Exception\UnexceptedPasswordFormatException;
use Zoe\Component\Password\Topology\NativePasswordTopologyManager;
use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface;
use Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;

/**
 * NativePasswordTopologyManager testcase
 * 
 * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyManagerTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::setLimit()
     */
    public function testIsSecure(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->any())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topology->expects($this->any())->method("getTopology")->will($this->returnValue("xxx"));
        
        $topologiesReturned = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"      =>  ["ff", "ll", "pp"]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with("FooGenerator", 6)->will($this->returnValue($topologiesReturned["FooGenerator"]));
        
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader);
        $this->assertNull($manager->setLimit(6));
        //make sure that load method from loaded is call once - test is below
        $manager->getRestrictedPasswordTopologies("FooGenerator");
        
        $this->assertTrue($manager->isSecure($topology));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     */
    public function testIsSecureWithNoCorrespondingTopologyGeneratorIdentifier(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->any())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topology->expects($this->any())->method("getTopology")->will($this->returnValue("xxx"));
        
        $topologiesReturned = TopologyShortcut::generateTopologies($this, [
            "BarGenerator"      =>  ["ff", "ll", "pp"]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with("FooGenerator", 6)->will($this->returnValue($topologiesReturned["BarGenerator"]));
        
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader, 6);
        
        $this->assertTrue($manager->isSecure($topology));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     */
    public function testIsSecureWhenNotSecure(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->any())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topology->expects($this->any())->method("getTopology")->will($this->returnValue("ff"));
        
        $topologiesReturned = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"      =>  ["ff", "ll", "pp"]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with("FooGenerator", 6)->will($this->returnValue($topologiesReturned["FooGenerator"]));
        
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader, 6);
        
        $this->assertFalse($manager->isSecure($topology));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::generate()
     */
    public function testGenerate(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();

        $topologyGenerated = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGenerated->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topologyGenerated->method("getTopology")->will($this->returnValue("Foo"));
        
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        $generator->expects($this->once())->method("format")->with($password)->will($this->returnValue($topologyGenerated));
        $generator->expects($this->once())->method("support")->with($password)->will($this->returnValue(true));
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader, 6);
        
        $topology = $manager->generate($password);
        
        $this->assertSame("FooGenerator", $topology->generatedBy());
        $this->assertSame("Foo", $topology->getTopology());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::generate()
     */
    public function testGetRestrictedPasswordTopologies(): void
    {
        $topologiesReturned = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"      =>  ["ff", "ll", "pp"]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with("FooGenerator", 4)->will($this->returnValue($topologiesReturned["FooGenerator"]));
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader, 4);
        
        $topologies = $manager->getRestrictedPasswordTopologies("FooGenerator");
        $this->assertCount(3, $topologies);
        
        foreach ($topologies as $topology) {
            $this->assertInstanceOf(PasswordTopology::class, $topology);
        }
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::generate()
     */
    public function testExceptionGenerateWhenLoaderFailsToGenerateATopology(): void
    {
        $this->expectException(UnexceptedPasswordFormatException::class);
        $this->expectExceptionMessage("Cannot generate a topology over given password with setted topology generator 'FooGenerator'");
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        $generator->expects($this->once())->method("getIdentifier")->will($this->returnValue("FooGenerator"));
        $generator->expects($this->once())->method("support")->with($password)->will($this->returnValue(false));
        
        $manager = new NativePasswordTopologyManager($generator, $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock(), 42);
        $manager->generate($password);
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager
     */
    public function testExceptionWhenNoLimitHasBeenDefined(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Cannot initialize a set of restricted password topologies as no limit has been defined");
        
        $manager = new NativePasswordTopologyManager(
            $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), 
            $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock());
        
        $manager->getRestrictedPasswordTopologies("FooGenerator");
    }
    
}
