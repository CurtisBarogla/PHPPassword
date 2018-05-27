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
use Ness\Component\Password\Validation\Rule\RestrictedValuePasswordRule;
use Ness\Component\Password\Password;

/**
 * RestrictedValuePasswordRule testcase
 * 
 * @see \Ness\Component\Password\Validation\Rule\RestrictedValuePasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedValuePasswordRuleTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\RestrictedValuePasswordRule::comply()
     */
    public function testComply(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(2))->method("get")->will($this->onConsecutiveCalls("Foo", "Moz"));
        
        $rule = new RestrictedValuePasswordRule("Foo", ["Foo", "Bar"]);
        
        $this->assertFalse($rule->comply($password));
        $this->assertTrue($rule->comply($password));
    }
    
}
