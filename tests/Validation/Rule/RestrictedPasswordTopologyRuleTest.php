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
use Zoe\Component\Password\Topology\PasswordTopologyCollection;
use Zoe\Component\Internal\GeneratorTrait;
use Zoe\Component\Password\Exception\UnexceptedPasswordFormatException;

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
    
    use GeneratorTrait;
    
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
        
        $rule = new RestrictedPasswordTopologyRule("Foo", "Bar", $manager, 3);
        
        $this->assertTrue($rule->comply($password));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::getError()
     */
    public function testComplyFail(): void
    {
        $restrictedTopologies = TopologyShortcut::generateTopologies($this, ["FooGenerator" => ["foo", "bar", "moz"]])["FooGenerator"];
        $restrictedCollection = $this->getMockBuilder(PasswordTopologyCollection::class)->getMock();
        $restrictedCollection->expects($this->once())->method("getIterator")->will($this->returnValue($this->getGenerator($restrictedTopologies)));
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        
        $topologyGenerated = $this->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
        $topologyGenerated->expects($this->once())->method("getTopology")->will($this->returnValue("foo"));
        
        $manager = $this->getMockBuilder(PasswordTopologyManagerInterface::class)->getMock();
        $manager->expects($this->once())->method("setLimit")->with(3)->will($this->returnValue(null));
        $manager->expects($this->once())->method("generate")->with($password)->will($this->returnValue($topologyGenerated));
        $manager->expects($this->once())->method("isSecure")->with($topologyGenerated)->will($this->returnValue(false));
        $manager->expects($this->once())->method("getRestrictedPasswordTopologies")->will($this->returnValue($restrictedCollection));
        
        $rule = new RestrictedPasswordTopologyRule("Foo {:restricted_topologies:} - {:current_topology:}", "Bar", $manager, 3);
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("Foo foo, bar, moz - foo", $rule->getError());
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::comply()
     * @see \Zoe\Component\Password\Validation\Rule\RestrictedPasswordTopologyRule::getError()
     */
    public function testComplyFailWhenTopologyCannotBeGenerated(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        
        $manager = $this->getMockBuilder(PasswordTopologyManagerInterface::class)->getMock();
        $manager->expects($this->once())->method("generate")->with($password)->will($this->throwException(new UnexceptedPasswordFormatException()));
        
        $rule = new RestrictedPasswordTopologyRule("Foo", "BarError", $manager, 3);
        
        $this->assertFalse($rule->comply($password));
        $this->assertSame("BarError", $rule->getError());
    }
    
}
