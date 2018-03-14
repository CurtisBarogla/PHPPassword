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
use Zoe\Component\Password\Password;
use Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface;
use Zoe\Component\Password\Topology\Topology;
use Zoe\Component\Password\Topology\NativePasswordTopologyManager;
use Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Zoe\Component\Password\Exception\UnexceptedPasswordFormatException;
use ZoeTest\Component\Password\Common\TopologyShortcut;

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
     */
    public function testIsSecure(): void
    {
        $topologiesLoaded = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"      =>
                [
                    "fff",
                    "zzz",
                    "ppp",
                    "mmm"
                ],
            "LambdaGenerator"   =>  
                [
                    "ggg",
                    "aaa"
                ]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with(6)->will($this->returnValue($topologiesLoaded));
        $topology = $this->getMockBuilder(Topology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(6))->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topology->expects($this->exactly(4))->method("getTopology")->will($this->returnValue("aaa"));
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader, 6);
        
        $this->assertTrue($manager->isSecure($topology));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     */
    public function testIsSecureWithNoCorrespondingTopology(): void
    {
        $topologiesLoaded = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"      =>
            [
                "fff",
                "zzz",
                "ppp",
                "mmm"
            ],
            "LambdaGenerator"   =>
            [
                "ggg",
                "aaa"
            ]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with(6)->will($this->returnValue($topologiesLoaded));
        $topology = $this->getMockBuilder(Topology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(6))->method("generatedBy")->will($this->returnValue("NotRegisteredLoader"));
        $topology->expects($this->never())->method("getTopology");
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader, 6);
        
        $this->assertTrue($manager->isSecure($topology));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::isSecure()
     */
    public function testIsSecureWhenNotSecure(): void
    {
        $topologiesLoaded = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"      =>
            [
                "fff",
                "zzz",
                "ppp",
                "mmm"
            ],
            "LambdaGenerator"   =>
            [
                "ggg",
                "aaa"
            ]
        ]);
        $loader = $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock();
        $loader->expects($this->once())->method("load")->with(6)->will($this->returnValue($topologiesLoaded));
        $topology = $this->getMockBuilder(Topology::class)->disableOriginalConstructor()->getMock();
        $topology->expects($this->exactly(3))->method("generatedBy")->will($this->returnValue("FooGenerator"));
        $topology->expects($this->exactly(3))->method("getTopology")->will($this->returnValue("ppp"));
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        
        $manager = new NativePasswordTopologyManager($generator, $loader, 6);
        
        $this->assertFalse($manager->isSecure($topology));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\NativePasswordTopologyManager::generate()
     */
    public function testGenerate(): void
    {
        $topologyReturned = $this->getMockBuilder(Topology::class)->disableOriginalConstructor()->getMock();
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $generator = $this->getMockBuilder(PasswordTopologyGeneratorInterface::class)->getMock();
        $generator->expects($this->once())->method("support")->with($password)->will($this->returnValue(true));
        $generator->expects($this->once())->method("format")->with($password)->will($this->returnValue($topologyReturned));
        
        $manager = new NativePasswordTopologyManager($generator, $this->getMockBuilder(PasswordTopologyLoaderInterface::class)->getMock(), 42);
        
        $this->assertSame($topologyReturned, $manager->generate($password));
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
    
}
