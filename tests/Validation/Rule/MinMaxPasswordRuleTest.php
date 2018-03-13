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

namespace ZoeTest\Component\Password\Validation\Rule;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule;
use Zoe\Component\Password\Password;

/**
 * MinMaxPasswordRule testcase
 * 
 * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class MinMaxPasswordRuleTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::comply()
     */
    public function testComplyValid(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("count")->will($this->returnValue(10));
        $rule = new MinMaxPasswordRule("FooMin", "FooMax");
        
        $this->assertTrue($rule->comply($password));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::getError()
     */
    public function testComplyTooShort(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("count")->will($this->returnValue(3));
        $rule = new MinMaxPasswordRule("FooMin {:min:}", "FooMax");
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("FooMin 10", $rule->getError());
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::getError()
     */
    public function testComplyTooLong(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("count")->will($this->returnValue(6));
        $rule = new MinMaxPasswordRule("FooMin", "FooMax {:max:}", 0, 5);
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("FooMax 5", $rule->getError());
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::__construct()
     */
    public function testExceptionWhenMinGivenIsGreaterThanMaxGiven(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Min characters required cannot be greater or equal than max chars allowed. '42' min given - '0' max given");
        
        $rule = new MinMaxPasswordRule("FooMin", "FooMax", 42, 0);
        
        $rule->comply($this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock());
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::__construct()
     */
    public function testExceptionWhenMinGivenIsEqualThanMaxGiven(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Min characters required cannot be greater or equal than max chars allowed. '42' min given - '42' max given");
        
        $rule = new MinMaxPasswordRule("FooMin", "FooMax", 42, 42);
        
        $rule->comply($this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock());
    }
    
}
