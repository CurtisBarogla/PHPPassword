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
use Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule;
use Zoe\Component\Password\Password;

/**
 * CharRowLimitPasswordRule testcase
 * 
 * @see \Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CharRowLimitPasswordRuleTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule::comply()
     */
    public function testComply(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getExplodedPassword")->will($this->returnValue(["F", "o", "o"]));
        $rule = new CharRowLimitPasswordRule("FooError");
        
        $this->assertTrue($rule->comply($password));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule::getError()
     */
    public function testComplyError(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(2))->method("getExplodedPassword")->will($this->returnValue(["F", "o", "o", "o", "o"]));
        
        // with placeholder
        $rule = new CharRowLimitPasswordRule("Foo {:char:} Error", 4);
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("Foo o Error", $rule->getError());
        
        // without placeholder
        $rule = new CharRowLimitPasswordRule("Foo Error", 4);
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("Foo Error", $rule->getError());
    }
    
}
