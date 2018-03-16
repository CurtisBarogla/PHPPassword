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

namespace ZoeTest\Component\Password\Hash;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Hash\PlainTextPasswordHash;
use Zoe\Component\Password\Password;

/**
 * NullPasswordHash testcase
 * 
 * @see \Zoe\Component\Password\Hash\NullPasswordHash
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PlainTextPasswordHashTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Hash\PlainTextPasswordHash::hash()
     */
    public function testHash(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->once())->method("getValue")->will($this->returnValue("Foo"));
        
        $hash = new PlainTextPasswordHash();
        
        $this->assertSame("Foo", $hash->hash($password));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\PlainTextPasswordHash::isValid()
     */
    public function testIsValid(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $password->expects($this->exactly(2))->method("getValue")->will($this->returnValue("Foo"));
        $incorrectPassword = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $incorrectPassword->expects($this->once())->method("getValue")->will($this->returnValue("Bar"));
        
        
        $hash = new PlainTextPasswordHash();
        
        $hashed = $hash->hash($password);
        
        $this->assertTrue($hash->isValid($password, $hashed));
        $this->assertFalse($hash->isValid($incorrectPassword, $hashed));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\PlainTextPasswordHash::needsRehash()
     */
    public function testNeedsRehash(): void
    {
        $hash = new PlainTextPasswordHash();
        
        $this->assertFalse($hash->needsRehash("Foo"));
    }
    
}
