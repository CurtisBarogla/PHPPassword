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

namespace NessTest\Component\Password\Validation\Rule;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Password;
use Ness\Component\Password\RegexRange;
use Ness\Component\Password\Validation\Rule\RegexRangePasswordRule;

/**
 * RegexRangePasswordRule testcase
 * 
 * @see \Ness\Component\Password\Validation\Rule\RegexRangePasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangePasswordRuleTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\RegexRangePasswordRule::comply()
     */
    public function testComply(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(4))->method("get")->will($this->returnValue("foo"));
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $range->expects($this->exactly(4))->method("preg")->with("foo")->will($this->onConsecutiveCalls(null, 1, 2, 2));
        $range->expects($this->exactly(2))->method("getRanges");
        $range->expects($this->exactly(4))->method("count")->will($this->returnValue(2));
        
        $rule = new RegexRangePasswordRule("Foo", 2);
        $rule->setRange($range);
        $this->assertFalse($rule->comply($password));
        $this->assertFalse($rule->comply($password));
        $this->assertTrue($rule->comply($password));
        
        $rule = new RegexRangePasswordRule("Foo");
        $rule->setRange($range);
        $this->assertTrue($rule->comply($password));
    }
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\RegexRangePasswordRule::getError()
     */
    public function testGetError(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("get")->will($this->returnValue("foo"));
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $range->expects($this->once())->method("preg")->with("foo")->will($this->onConsecutiveCalls(null));
        $range->expects($this->once())->method("getRanges")->will($this->returnValue([
            "foo"   =>  ["regex" => ["a-z"], "min" => 1, "max" => 5],
            "bar"   =>  ["regex" => ["a-z"], "min" => 1, "max" => 5]
        ]));
        $range->expects($this->once())->method("count")->will($this->returnValue(2));
        
        $rule = new RegexRangePasswordRule("Min : {:foo_min:} - Max : {:foo_max:} - Required : {:required:} - Ranges : {:ranges:}", 2);
        $rule->setRange($range);
        $rule->comply($password);
        $this->assertSame("Min : 1 - Max : 5 - Required : 2 - Ranges : 2", $rule->getError());
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\RegexRangePasswordRule::comply()
     */
    public function testExceptionWhenRequiredRangeIsGreaterThanSettedOneIntoRegexRange(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Setted RegexRange has '1' ranges registered and rule is requiring '2'");
        
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("get")->will($this->returnValue("foo"));
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $range->expects($this->once())->method("preg")->with("foo")->will($this->returnValue(1));
        $range->expects($this->once())->method("count")->will($this->returnValue(1));
        
        $rule = new RegexRangePasswordRule("Foo", 2);
        $rule->setRange($range);
        $rule->comply($password);
    }
    
}
