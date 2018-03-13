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
use Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator;
use Zoe\Component\Password\Password;
use Zoe\Component\Password\Exception\UnexpectedPasswordFormatException;

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
     * Should be updated if get*CharacterRanges methods are updated
     * 
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::format()
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::format()
     */
    public function testFormat(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getExplodedPassword")->will($this->returnValue(["F", "o", "@", "0"]));
        
        $generator = new NativePasswordTopologyGenerator();
        
        $topology = $generator->format($password);
        $this->assertSame("ulsd", $topology->getTopology());
        $this->assertSame($generator->getIdentifier(), $topology->generatedBy());
    }
    
    /**
     * Should be updated if get*CharacterRanges methods are updated
     * 
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::support()
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::support()
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
     * Should be updated if get*CharacterRanges methods are updated
     * 
     * @see \Zoe\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::format()
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::format()
     */
    public function testExceptionFormatWhenInvalidPasswordIsGiven(): void
    {
        $this->expectException(UnexpectedPasswordFormatException::class);
        $this->expectExceptionMessage("This character 'é' is not handled by password topology generator 'NativePasswordTopologyGenerator");
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        // should be updated if given char is handled by generator
        $password->expects($this->once())->method("getExplodedPassword")->will($this->returnValue(["f", "é"]));
        
        $generator = new NativePasswordTopologyGenerator();
        $generator->format($password);
    }
    
}
