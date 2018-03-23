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
use Zoe\Component\Password\Validation\Rule\RestrictedPasswordValueRule;
use Zoe\Component\Password\Password;

/**
 * RestrictedPasswordValueRule testcase
 * 
 * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordValueRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedPasswordValueRuleTest extends TestCase
{
 
    /**
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordValueRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordValueRule::getError()
     */
    public function testComply(): void
    {
        $restrictedPasswords = [
            "foo",
            "bar",
            "moz"
        ];
        
        $rule = new RestrictedPasswordValueRule("Foo Error Message", $restrictedPasswords);
        
        $this->assertFalse($rule->comply(new Password("foo")));
        $this->assertTrue($rule->comply(new Password("poz")));
        $this->assertSame("Foo Error Message", $rule->getError());
    }
    
}
