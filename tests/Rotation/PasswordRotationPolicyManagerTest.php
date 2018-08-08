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

namespace NessTest\Component\Password\Rotation;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Rotation\PasswordRotationPolicyManager;
use Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderInterface;
use Ness\Component\Password\Rotation\Policy\PasswordRotationPolicyInterface;
use Ness\Component\User\UserInterface;

/**
 * PasswordRotationPolicyManager testcase
 * 
 * @see \Ness\Component\Password\Rotation\PasswordRotationPolicyManager
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordRotationPolicyManagerTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Rotation\PasswordRotationPolicyManager::addPolicy()
     */
    public function testAddPolicy(): void
    {
        $manager = new PasswordRotationPolicyManager($this->getMockBuilder(LastPasswordRotationProviderInterface::class)->getMock());
        
        $this->assertNull($manager->addPolicy($this->getMockBuilder(PasswordRotationPolicyInterface::class)->getMock()));
    }
    
    /**
     * @see \Ness\Component\Password\Rotation\PasswordRotationPolicyManager::shouldRotate()
     */
    public function testShouldRotate(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        
        $rotation = new \DateTimeImmutable("NOW - 42 days");
        
        $provider = $this->getMockBuilder(LastPasswordRotationProviderInterface::class)->getMock();
        $provider->expects($this->any())->method("provide")->with($user)->will($this->returnValue($rotation));
        
        $policyFoo = $this->getMockBuilder(PasswordRotationPolicyInterface::class)->getMock();
        $policyBar = $this->getMockBuilder(PasswordRotationPolicyInterface::class)->getMock();
        $policyMoz = $this->getMockBuilder(PasswordRotationPolicyInterface::class)->getMock();
        $policyFoz = $this->getMockBuilder(PasswordRotationPolicyInterface::class)->getMock();

        $policyFoo->expects($this->exactly(4))->method("support")->withConsecutive([$user])->will($this->onConsecutiveCalls(false, false, false, false));
        $policyFoo->expects($this->never())->method("apply");
        
        $policyBar->expects($this->exactly(4))->method("support")->withConsecutive([$user])->will($this->onConsecutiveCalls(false, false, false, true));
        $policyBar->expects($this->once())->method("apply")->with($rotation)->will($this->returnValue(true));
        
        $policyMoz->expects($this->exactly(3))->method("support")->withConsecutive([$user])->will($this->onConsecutiveCalls(false, false, true));
        $policyMoz->expects($this->once())->method("apply")->with($rotation)->will($this->returnValue(true));
        
        $policyFoz->expects($this->exactly(2))->method("support")->withConsecutive([$user])->will($this->onConsecutiveCalls(true, true));
        $policyFoz->expects($this->exactly(2))->method("apply")->withConsecutive([$rotation])->will($this->onConsecutiveCalls(false, true));
        
        $manager = new PasswordRotationPolicyManager($provider);
        
        $manager->addPolicy($policyFoo);
        $manager->addPolicy($policyBar);
        $manager->addPolicy($policyMoz);
        $manager->addPolicy($policyFoz);
        
        $this->assertFalse($manager->shouldRotate($user));
        $this->assertTrue($manager->shouldRotate($user));
        $this->assertTrue($manager->shouldRotate($user));
        $this->assertTrue($manager->shouldRotate($user));
    }
    
    /**
     * @see \Ness\Component\Password\Rotation\PasswordRotationPolicyManager::shouldRotate()
     */
    public function testShouldRotateWithNoPolicyOrValidProvider(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $provider = $this->getMockBuilder(LastPasswordRotationProviderInterface::class)->getMock();
        $provider->expects($this->once())->method("provide")->with($user)->will($this->returnValue(null));
        
        $manager = new PasswordRotationPolicyManager($provider);
        
        $this->assertFalse($manager->shouldRotate($user));
        
        $manager->addPolicy($this->getMockBuilder(PasswordRotationPolicyInterface::class)->getMock());
        
        $this->assertFalse($manager->shouldRotate($user));
    }
    
}
