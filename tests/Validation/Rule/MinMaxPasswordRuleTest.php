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
        $rule = new MinMaxPasswordRule("FooMin", "FooMax");
        
        $this->assertTrue($rule->comply(\str_repeat("Foo", 10)));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::getError()
     */
    public function testComplyTooShort(): void
    {
        $rule = new MinMaxPasswordRule("FooMin", "FooMax");
        
        $this->assertFalse($rule->comply("Foo"));
        $this->assertSame("FooMin", $rule->getError());
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\MinMaxPasswordRule::getError()
     */
    public function testComplyTooLong(): void
    {
        $rule = new MinMaxPasswordRule("FooMin", "FooMax", 0, 5);
        
        $this->assertFalse($rule->comply("FooBare"));
        $this->assertSame("FooMax", $rule->getError());
    }
    
}
