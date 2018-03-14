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
use Zoe\Component\Password\Password;
use Zoe\Component\Password\Topology\PasswordTopologyManagerInterface;
use Zoe\Component\Password\Topology\PasswordTopology;
use Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule;
use ZoeTest\Component\Password\Common\TopologyShortcut;

/**
 * RestrictedPasswordTopologyRule testcase
 * 
 * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedPasswordTopologyRuleTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::comply()
     */
    public function testComplyValid(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $topologyGenerated = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $manager = $this->getMockBuilder(PasswordTopologyManagerInterface::class)->getMock();
        $manager->expects($this->once())->method("setLimit")->with(3)->will($this->returnValue(null));
        $manager->expects($this->once())->method("generate")->with($password)->will($this->returnValue($topologyGenerated));
        $manager->expects($this->once())->method("isSecure")->with($topologyGenerated)->will($this->returnValue(true));
        
        $rule = new RestrictedPasswordTopologyRule("Foo", $manager, 3);
        
        $this->assertTrue($rule->comply($password));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::getError()
     */
    public function testComplyFail(): void
    {
        $restrictedTopologies = TopologyShortcut::generateTopologies($this, [
            "FooGenerator"  =>  ["fff", "ggg", "hhh"]
        ]);
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $topologyGenerated = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGenerated->expects($this->once())->method("getTopology")->will($this->returnValue("ggg"));
        $topologyGenerated->expects($this->once())->method("generatedBy")->will($this->returnValue("FooGenerator"));
        
        $manager = $this->getMockBuilder(PasswordTopologyManagerInterface::class)->getMock();
        $manager->expects($this->once())->method("generate")->with($password)->will($this->returnValue($topologyGenerated));
        $manager->expects($this->once())->method("isSecure")->with($topologyGenerated)->will($this->returnValue(false));
        $manager->expects($this->once())->method("getRestrictedPasswordTopologies")->with("FooGenerator")->will($this->returnValue($restrictedTopologies["FooGenerator"]));
        
        $rule = new RestrictedPasswordTopologyRule("Foo {:restricted_topologies:} - {:current_topology:}", $manager, 3);
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("Foo fff, ggg, hhh - ggg", $rule->getError());
    }
    
}
