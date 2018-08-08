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

namespace NessTest\Component\Password\Rotation\Provider;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\User\UserInterface;
use Ness\Component\Password\Rotation\Provider\UserAttributePasswordRotationProvider;

/**
 * UserAttributePasswordRotationProvider testcase
 * 
 * @see \Ness\Component\Password\Rotation\Provider\UserAttributePasswordRotationProvider
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UserAttributePasswordRotationProviderTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Rotation\Provider\UserAttributePasswordRotationProvider::provide()
     */
    public function testProvide(): void
    {
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        $user
            ->expects($this->exactly(4))
            ->method("getAttribute")
            ->withConsecutive([UserAttributePasswordRotationProvider::USER_ATTRIBUTE_ROTATION_FLAG])
            ->will($this->onConsecutiveCalls(
                null,
                "not valid",
                new \DateTime("NOW - 42 days"),
                new \DateTimeImmutable("NOW - 42 days"),
                \time() - 3600*24*42
                ));
        
        $provider = new UserAttributePasswordRotationProvider();
        
        $this->assertNull($provider->provide($user));
        $this->assertNull($provider->provide($user));
        for ($i = 0; $i < 2; $i++) {
            $this->assertEquals((new \DateTimeImmutable("NOW - 42 days"))->format("d/m/Y"), $provider->provide($user)->format("d/m/Y"));            
        }
    }
    
}
