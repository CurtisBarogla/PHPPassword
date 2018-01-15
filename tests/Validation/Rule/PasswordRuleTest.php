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
use ZoeTest\Component\Password\Fixture\FooPasswordRule;

/**
 * PasswordRule testcase
 * 
 * @see \Zoe\Component\Password\Validation\Rule\PasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordRuleTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRule::comply()
     */
    public function testComply(): void
    {
        $rule = new FooPasswordRule("FooError");
        
        $this->assertTrue($rule->comply("Foo"));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRule::getError()
     */
    public function testGetError(): void
    {
        $rule = new FooPasswordRule("FooError");
        
        $this->assertSame("FooError", $rule->getError());
    }
    
}
