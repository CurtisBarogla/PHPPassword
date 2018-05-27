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
use Ness\Component\Password\Validation\Rule\CharRowLimitPasswordRule;

/**
 * CharRowLimitPasswordRule testcase
 * 
 * @see \Ness\Component\Password\Validation\Rule\CharRowLimitPasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CharRowLimitPasswordRuleTest extends PasswordTestCase
{
    /**
     * @see \Ness\Component\Password\Validation\Rule\CharRowLimitPasswordRule::comply()
     */
    public function testComply(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(3))->method("getExploded")->will($this->onConsecutiveCalls(
            ["f", "o", "é", "é"],
            ["f", "f", "o", "o", "o"],
            ["f", "o", "o", "o"]
        ));
        
        $rule = new CharRowLimitPasswordRule("Foo");
        $this->assertTrue($rule->comply($password));
        $this->assertFalse($rule->comply($password));
        
        $rule = new CharRowLimitPasswordRule("Foo", 3);
        $this->assertTrue($rule->comply($password));
    }
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\CharRowLimitPasswordRule::getError()
     */
    public function testGetError(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(2))->method("getExploded")->will($this->returnValue(
            ["f", "o", "o", "o"]
        ));
        
        $rule = new CharRowLimitPasswordRule("Foo");
        $rule->comply($password);
        $this->assertSame("Foo", $rule->getError());
        
        $rule = new CharRowLimitPasswordRule("Limit : {:limit:} - Char : {:char:}", 2);
        $rule->comply($password);
        $this->assertSame("Limit : 2 - Char : o", $rule->getError());
    }
    
}
