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
use Zoe\Component\Password\Hash\NullPasswordHash;

/**
 * NullPasswordHash testcase
 * 
 * @see \Zoe\Component\Password\Hash\NullPasswordHash
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NullPasswordHashTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Hash\NullPasswordHash::hash()
     */
    public function testHash(): void
    {
        $hash = new NullPasswordHash();
        
        $this->assertSame("Foo", $hash->hash("Foo"));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NullPasswordHash::isValid()
     */
    public function testIsValid(): void
    {
        $hash = new NullPasswordHash();
        
        $hashed = $hash->hash("Foo");
        
        $this->assertTrue($hash->isValid("Foo", $hashed));
        $this->assertFalse($hash->isValid("Bar", $hashed));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NullPasswordHash::needsRehash()
     */
    public function testNeedsRehash(): void
    {
        $hash = new NullPasswordHash();
        
        $this->assertFalse($hash->needsRehash("Foo"));
    }
    
}
