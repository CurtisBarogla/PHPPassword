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
use Zoe\Component\Internal\GeneratorTrait;
use Zoe\Component\Password\Password;
use Zoe\Component\Password\Exception\UnexceptedPasswordFormatException;
use Zoe\Component\Password\Topology\NativePasswordTopologyManager;
use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Topology\PasswordTopologyCollection;
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
    
    use GeneratorTrait;

    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::setLimit()
     */
    public function testIsSecure(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->once())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topologyGiven->expects($this->once())->method("getTopology")->will($this->returnValue("foo"));
        
        $collectionReturned = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $collectionReturned->expects($this->once())->method("getCollectionGeneratorIdentifier")->will($this->returnValue("FooGenerator"));
        $collectionReturned->expects($this->once())->method("offsetExists")->with("foo")->will($this->returnValue(false));
        
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with($topologyGiven, 42)->will($this->returnValue($collectionReturned));
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader);
        $manager->setLimit(42);
        
        $this->assertTrue($manager->isSecure($topologyGiven));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::setLimit()
     */
    public function testIsSecureWhenLoaderReturnNull(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->never())->method("generatedBy");
        $topologyGiven->expects($this->never())->method("getTopology");
        
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with($topologyGiven, 42)->will($this->returnValue(null));
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader);
        $manager->setLimit(42);
        
        $this->assertTrue($manager->isSecure($topologyGiven));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::setLimit()
     */
    public function testIsSecureWithNoCorrespondingTopologyGeneratorIdentifier(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->once())->method("generatedBy")->will($this->returnValue("BarGenerator"));
        $topologyGiven->expects($this->never())->method("getTopology");
        
        $collectionReturned = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $collectionReturned->expects($this->once())->method("getCollectionGeneratorIdentifier")->will($this->returnValue("FooGenerator"));
        $collectionReturned->expects($this->never())->method("offsetExists");
        
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with($topologyGiven, 42)->will($this->returnValue($collectionReturned));
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader);
        $manager->setLimit(42);
        
        $this->assertTrue($manager->isSecure($topologyGiven));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::setLimit()
     */
    public function testIsSecureWhenNotSecure(): void
    {
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGiven->expects($this->once())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topologyGiven->expects($this->once())->method("getTopology")->will($this->returnValue("foo"));
        
        $collectionReturned = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $collectionReturned->expects($this->once())->method("getCollectionGeneratorIdentifier")->will($this->returnValue("FooGenerator"));
        $collectionReturned->expects($this->once())->method("offsetExists")->with("foo")->will($this->returnValue(true));
        
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with($topologyGiven, 42)->will($this->returnValue($collectionReturned));
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader);
        $manager->setLimit(42);
        
        $this->assertFalse($manager->isSecure($topologyGiven));
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
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::getRestrictedPasswordTopologies()
     */
    public function testGetRestrictedPasswordTopologies(): void
    {
        $collectionReturned = $this->getMockBuilder(PasswordTopologyCollection::class)->getMock();
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $topologyGiven = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        
        $loader->expects($this->once())->method("load")->with($topologyGiven, 42)->will($this->returnValue($collectionReturned));
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader);
        $manager->setLimit(42);
        
        $manager->isSecure($topologyGiven);
        
        $this->assertSame($collectionReturned, $manager->getRestrictedPasswordTopologies());
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
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::getRestrictedPasswordTopologies()
     */
    public function testExceptionWhenRestrictedTopologyPropertyIsNotAlreadyInitialized(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("To get restricted password topologies, you need to initialize the manager by giving a PasswordTopology. Use isSecure method");
        
        $manager = new NativePasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock());
        $manager->setLimit(3);
        $manager->getRestrictedPasswordTopologies();
    }
    
}
