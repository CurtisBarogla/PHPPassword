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
use Ness\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule;
use Ness\Component\Password\Password;
use Ness\Component\Password\Topology\PasswordTopologyManagerInterface;
use Ness\Component\Password\Topology\PasswordTopology;
use Ness\Component\Password\Exception\UnsupportedPasswordException;

/**
 * RestrictedPasswordTopologyRule testcase
 * 
 * @see \Ness\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedPasswordTopologyRuleTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::comply()
     */
    public function testComply(): void
    {
        $topology = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $manager = $this->getMockBuilder(PasswordTopologyManagerInterface::class)->getMock();
        $manager->expects($this->exactly(2))->method("isSecure")->with($topology)->will($this->onConsecutiveCalls(true, false));
        $manager->expects($this->exactly(3))->method("generate")->with($password)->will($this->onConsecutiveCalls($topology, $topology, $this->throwException(new UnsupportedPasswordException())));
        
        $rule = new RestrictedPasswordTopologyRule("Foo", $manager);
        
        $this->assertTrue($rule->comply($password));
        $this->assertFalse($rule->comply($password));
        $this->assertFalse($rule->comply($password));
    }
    
}
