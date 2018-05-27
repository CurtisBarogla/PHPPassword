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

namespace NessTest\Component\Password\Hash;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Hash\PlainPasswordHash;
use Ness\Component\Password\Password;

/**
 * PlainPassword testcase
 * 
 * @see \Ness\Component\Password\Hash\PlainPasswordHash
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PlainPasswordTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Hash\PlainPasswordHash::hash()
     */
    public function testHash(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("get")->will($this->returnValue("Foo"));
        
        $hash = new PlainPasswordHash();
        
        $this->assertSame("Foo", $hash->hash($password));
    }
    
    /**
     * @see \Ness\Component\Password\Hash\PlainPasswordHash::verify()
     */
    public function testVerify(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(2))->method("get")->will($this->onConsecutiveCalls("Foo", "Bar"));
        
        $hash = new PlainPasswordHash();
        
        $this->assertTrue($hash->verify($password, "Foo"));
        $this->assertFalse($hash->verify($password, "Foo"));
    }
    
    /**
     * @see \Ness\Component\Password\Hash\PlainPasswordHash::needsRehash()
     */
    public function testNeedsRehash(): void
    {
        $hash = new PlainPasswordHash();
        
        $this->assertFalse($hash->needsRehash("Foo"));
    }
    
}
