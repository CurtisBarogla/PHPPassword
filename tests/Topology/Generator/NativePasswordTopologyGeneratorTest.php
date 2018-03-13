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

namespace ZoeTest\Component\Password\Topology\Generator;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Password;
use Zoe\Component\Password\Exception\UnexpectedMethodCallException;
use Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator;

/**
 * NativePasswordTopologyGenerator testcase
 * 
 * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyGeneratorTest extends TestCase
{
 
    /**
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::format()
     */
    public function testFormatWhenConstructorParamRangesIsNull(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getExplodedPassword")->will($this->returnValue(["F", "o", "@", "0"]));
        
        $generator = new NativePasswordTopologyGenerator();
        $generator->support($password);
        
        $topology = $generator->format($password);
        
        $this->assertSame("ulsd", $topology->getTopology());
        $this->assertSame($generator->getIdentifier(), $topology->generatedBy());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::format()
     */
    public function testFormatWhenConstructorParamRangesIsGiven(): void
    {
        $topologyRanges = [
            "f"     =>  ["A-C"],
            "p"     =>  ["D-V"],
            "l"     =>  ["W-Z"],
            "n"     =>  ["a-z"]
        ];
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getExplodedPassword")->will($this->returnValue(["B", "R", "T", "X", "z", "r", "t"]));
        
        $generator = new NativePasswordTopologyGenerator($topologyRanges);
        $generator->support($password);
        
        $topology = $generator->format($password);
        
        $this->assertSame("fpplnnn", $topology->getTopology());
        $this->assertSame($generator->getIdentifier(), $topology->generatedBy());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::support()
     */
    public function testSupport(): void
    {
        $generator = new NativePasswordTopologyGenerator();
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getValue")->will($this->returnValue("A0@z"));
        
        // basic...
        $this->assertTrue($generator->support($password));
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getValue")->will($this->returnValue("A0@zé"));
        
        // basic too...
        $this->assertFalse($generator->support($password));
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::getIdentifier()
     */
    public function testGetIdentifier(): void
    {
        $generator = new NativePasswordTopologyGenerator();
        
        $this->assertSame("NativePasswordTopologyGenerator", $generator->getIdentifier());
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::format()
     */
    public function testExceptionFormatWhenSupportMethodIsNotCalled(): void
    {
        $this->expectException(UnexpectedMethodCallException::class);
        $this->expectExceptionMessage("Password topology cannot be generated if support method has not been called yet over 'NativePasswordTopologyGenerator' topology generator");
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        
        $generator = new NativePasswordTopologyGenerator();
        $generator->format($password);
    }
    
}
