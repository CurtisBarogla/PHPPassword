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
        $rule = new CharRowLimitPasswordRule("FooError");
        
        $this->assertTrue($rule->comply("Foo"));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\CharRowLimitPasswordRule::getError()
     */
    public function testComplyError(): void
    {
        // with placeholder
        $rule = new CharRowLimitPasswordRule("Foo {:char:} Error", 4);
        
        $this->assertFalse($rule->comply("Foooo"));
        $this->assertSame("Foo o Error", $rule->getError());
        
        // without placeholder
        $rule = new CharRowLimitPasswordRule("Foo Error", 4);
        
        $this->assertFalse($rule->comply("Foooo"));
        $this->assertSame("Foo Error", $rule->getError());
    }
    
}
