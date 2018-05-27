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

namespace NessTest\Component\Password\Topology\Generator;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Topology\Generator\RegexRangePasswordTopologyGenerator;
use Ness\Component\Password\RegexRange;
use Ness\Component\Password\Password;
use Ness\Component\Password\Exception\UnsupportedPasswordException;

/**
 * RegexRangePasswordTopologyGenerator testcase
 * 
 * @see \Ness\Component\Password\Topology\Generator\RegexRangePasswordTopologyGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangePasswordTopologyGeneratorTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\Generator\RegexRangePasswordTopologyGenerator::generate()
     */
    public function testGenerate(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("get")->will($this->returnValue("Foo"));
        $password->expects($this->once())->method("getExploded")->will($this->returnValue(['F', 'o', 'o']));
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $range->expects($this->once())->method("preg")->with("Foo")->will($this->returnValue(1));
        $range->expects($this->exactly(3))->method("pregRange")->withConsecutive(['F'], ['o'], ['o'])->will($this->onConsecutiveCalls('u', 'l', 'l'));
        $range->expects($this->once())->method("getIdentifier")->will($this->returnValue("Foo"));
        
        $generator = new RegexRangePasswordTopologyGenerator();
        $generator->setRange($range);
        
        $topology = $generator->generate($password);
        $this->assertSame("ull", $topology->get());
        $this->assertSame("Foo", $topology->generatedBy());
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Generator\RegexRangePasswordTopologyGenerator::getIdentifier()
     */
    public function testGetIdentifier(): void
    {
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $range->expects($this->once())->method("getIdentifier")->will($this->returnValue("Foo"));
        
        $generator = new RegexRangePasswordTopologyGenerator();
        $generator->setRange($range);
        
        $this->assertSame("Foo", $generator->getIdentifier());
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Ness\Component\Password\Topology\Generator\RegexRangePasswordTopologyGenerator::generate()
     */
    public function testExceptionWhenPasswordIsNotHandled(): void
    {
        $this->expectException(UnsupportedPasswordException::class);
        $this->expectExceptionMessage("Topology cannot be generated over given password with 'Foo' PasswordTopologyGenerator");
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("get")->will($this->returnValue("Foo"));
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $range->expects($this->once())->method("preg")->with("Foo")->will($this->returnValue(null));
        $range->expects($this->once())->method("getIdentifier")->will($this->returnValue("Foo"));
        
        $generator = new RegexRangePasswordTopologyGenerator("Foo");
        $generator->setRange($range);
        $generator->generate($password);
    }
    
}
