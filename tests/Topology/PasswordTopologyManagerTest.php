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

use Ness\Component\Password\Password;
use Ness\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface;
use Ness\Component\Password\Topology\PasswordTopologyManager;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Ness\Component\Password\Topology\PasswordTopology;
use Ness\Component\Password\Topology\PasswordTopologyCollection;

/**
 * PasswordTopologyLoaderManager testcase
 * 
 * @see \Ness\Component\Password\Topology\PasswordTopologyManager
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyLoaderManagerTest extends PasswordTopologyTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopologyManager::generate()
     */
    public function testGenerate(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $topologyReturned = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyReturned->expects($this->once())->method("get")->will($this->returnValue("Foo"));
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        $generator->expects($this->once())->method("generate")->with($password)->will($this->returnValue($topologyReturned));
        
        $manager = new PasswordTopologyManager($generator, $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock(), 42);
        
        $this->assertSame("Foo", $manager->generate($password)->get());
    }
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopologyManager::isSecure()
     */
    public function testIsSecure(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(2))->method("generatedBy")->will($this->returnValue("Foo"));
        $collectionReturned = $this->getMockBuilder(PasswordTopologyCollection::class)->disableOriginalConstructor()->getMock();
        $collectionReturned->expects($this->exactly(2))->method("has")->with($topology)->will($this->onConsecutiveCalls(true, false));
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->exactly(2))->method("load")->with(3, "Foo")->will($this->returnValue($collectionReturned));
        
        $manager = new PasswordTopologyManager($this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock(), $loader, 3);
        
        $this->assertFalse($manager->isSecure($topology));
        $this->assertTrue($manager->isSecure($topology));
    }
    
}
