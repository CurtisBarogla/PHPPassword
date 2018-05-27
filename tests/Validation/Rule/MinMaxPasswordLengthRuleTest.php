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
use Ness\Component\Password\Validation\Rule\MinMaxPasswordLengthRule;
use Ness\Component\Password\Password;

/**
 * MinMaxPasswordLengthRule testcase
 * 
 * @see \Ness\Component\Password\Validation\Rule\MinMaxPasswordLengthRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class MinMaxPasswordLengthRuleTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\MinMaxPasswordLengthRule::comply()
     */
    public function testComply(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(4))->method("count")->will($this->returnValue(20));
        
        $rule = new MinMaxPasswordLengthRule("Foo", 10, 20);
        $this->assertTrue($rule->comply($password));
        
        $rule = new MinMaxPasswordLengthRule("Foo", 20);
        $this->assertTrue($rule->comply($password));
        
        $rule = new MinMaxPasswordLengthRule("Foo", 21);
        $this->assertFalse($rule->comply($password));
        
        $rule = new MinMaxPasswordLengthRule("Foo", 10, 19);
        $this->assertFalse($rule->comply($password));
    }
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\MinMaxPasswordLengthRule::getError()
     */
    public function testGetError(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(2))->method("count")->will($this->returnValue(1));
        
        $rule = new MinMaxPasswordLengthRule("No Placeholder");
        $rule->comply($password);
        
        $this->assertSame("No Placeholder", $rule->getError());
        
        $rule = new MinMaxPasswordLengthRule("Min : {:min:} - Max : {:max:} - Current {:current:}");
        $rule->comply($password);
        
        $this->assertSame("Min : 10 - Max : 128 - Current 1", $rule->getError());
    }
    
}
