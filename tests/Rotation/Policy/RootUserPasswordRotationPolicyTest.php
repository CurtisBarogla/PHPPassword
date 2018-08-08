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

namespace NessTest\Component\Password\Rotation\Policy;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\User\UserInterface;
use Ness\Component\Password\Rotation\Policy\RootUserPasswordRotationPolicy;
use Ness\Component\Authentication\User\AuthenticatedUserInterface;

/**
 * RootUserPasswordRotationPolicy testcase
 * 
 * @see \Ness\Component\Password\Rotation\Policy\RootUserPasswordRotationPolicy
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RootUserPasswordRotationPolicyTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Rotation\Policy\RootUserPasswordRotationPolicy::apply()
     */
    public function testApply(): void
    {
        // default interval
        $policy = new RootUserPasswordRotationPolicy();
        
        $this->assertTrue($policy->apply(new \DateTimeImmutable("NOW - 91 days")));
        $this->assertFalse($policy->apply(new \DateTimeImmutable("NOW - 89 days")));
        
        // specified interval
        $policy = new RootUserPasswordRotationPolicy(new \DateInterval("P10D"));
        
        $this->assertTrue($policy->apply(new \DateTimeImmutable("NOW - 10 days")));
        $this->assertFalse($policy->apply(new \DateTimeImmutable("NOW - 9 days")));
    }
    
    /**
     * @see \Ness\Component\Password\Rotation\Policy\RootUserPasswordRotationPolicy::support()
     */
    public function testSupportWhenUserNotAuthenticated(): void
    {
        $policy = new RootUserPasswordRotationPolicy();
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        
        $this->assertFalse($policy->support($user));
    }
    
    /**
     * @see \Ness\Component\Password\Rotation\Policy\RootUserPasswordRotationPolicy::support()
     */
    public function testSupportWhenAuthenticatedButNotRoot(): void
    {
        $policy = new RootUserPasswordRotationPolicy();
        $user = $this->getMockBuilder(AuthenticatedUserInterface::class)->getMock();
        $user->expects($this->once())->method("isRoot")->will($this->returnValue(false));

        $this->assertFalse($policy->support($user));
    }
    
    /**
     * @see \Ness\Component\Password\Rotation\Policy\RootUserPasswordRotationPolicy::support()
     */
    public function testSupportWhenAuthenticatedButAndRoot(): void
    {
        $policy = new RootUserPasswordRotationPolicy();
        $user = $this->getMockBuilder(AuthenticatedUserInterface::class)->getMock();
        $user->expects($this->once())->method("isRoot")->will($this->returnValue(true));
        
        $this->assertTrue($policy->support($user));
    }
    
    
}
