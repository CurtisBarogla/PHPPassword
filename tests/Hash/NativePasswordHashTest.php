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
use Zoe\Component\Internal\ReflectionTrait;
use Zoe\Component\Password\Hash\NativePasswordHash;

/**
 * NativePasswordHash testcase
 * 
 * @see \Zoe\Component\Password\Hash\NativePasswordHash
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordHashTest extends TestCase
{
    
    use ReflectionTrait;
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::__construct()
     */
    public function testInit(): void
    {
        // defaults options bcrypt
        $hash = new NativePasswordHash();
        $options = $this->reflection_getPropertyValue($hash, new \ReflectionClass($hash), "options");
        
        $this->assertSame(["cost" => PASSWORD_BCRYPT_DEFAULT_COST], $options);
        
        // defined options bcrypt
        $hash = new NativePasswordHash(PASSWORD_BCRYPT, ["cost" => 12]);
        $options = $this->reflection_getPropertyValue($hash, new \ReflectionClass($hash), "options");
        
        $this->assertSame(["cost" => 12], $options);
        
        
        if(\defined("PASSWORD_ARGON2I")) {
            // defaults options argon
            $hash = new NativePasswordHash(PASSWORD_ARGON2I);
            $options = $this->reflection_getPropertyValue($hash, new \ReflectionClass($hash), "options");
            
            $this->assertSame([
                "memory_cost"   =>  PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                "time_cost"     =>  PASSWORD_ARGON2_DEFAULT_TIME_COST,
                "threads"       =>  PASSWORD_ARGON2_DEFAULT_THREADS
            ], $options);
            
            // defined options argon
            $hash = new NativePasswordHash(PASSWORD_ARGON2I, ["time_cost" => 42, "threads" => 6]);
            $options = $this->reflection_getPropertyValue($hash, new \ReflectionClass($hash), "options");
            
            $this->assertSame([
                "memory_cost"   =>  PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                "time_cost"     =>  42,
                "threads"       =>  6
            ], $options);
        }
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::hash()
     */
    public function testHashBCrypt(): void
    {
        $hash = new NativePasswordHash();
        
        $this->assertNotFalse($hash->hash("Foo"));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::hash()
     */
    public function testHashArgon(): void
    {
        if(!\defined("PASSWORD_ARGON2I"))
            $this->markTestSkipped("PASSWORD_ARGON2I not defined");
        
        $hash = new NativePasswordHash(PASSWORD_ARGON2I);
        
        $this->assertNotFalse($hash->hash("Foo"));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::isValid()
     */
    public function testIsValidBCrypt(): void
    {
        $hash = new NativePasswordHash();
        
        $hashed = $hash->hash("Foo");
        
        $this->assertTrue($hash->isValid("Foo", $hashed));
        $this->assertFalse($hash->isValid("Bar", $hashed));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::isValid()
     */
    public function testIsValidArgon(): void
    {
        if(!\defined("PASSWORD_ARGON2I"))
            $this->markTestSkipped("PASSWORD_ARGON2I not defined");
        
        $hash = new NativePasswordHash(PASSWORD_ARGON2I);
        
        $hashed = $hash->hash("Foo");
        
        $this->assertTrue($hash->isValid("Foo", $hashed));
        $this->assertFalse($hash->isValid("Bar", $hashed));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::needsRehash()
     */
    public function testNeedsRehashBCrypt(): void
    {
        $hash = new NativePasswordHash();
        
        $default = $hash->hash("Foo");
        
        $this->assertFalse($hash->needsRehash($default));
        
        $hash = new NativePasswordHash(PASSWORD_BCRYPT, ["cost" => 15]);
        
        $this->assertTrue($hash->needsRehash($default));
    }
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::needsRehash()
     */
    public function testNeedsRehashArgon(): void
    {
        if(!\defined("PASSWORD_ARGON2I"))
            $this->markTestSkipped("PASSWORD_ARGON2I not defined");
        
        $hash = new NativePasswordHash(PASSWORD_ARGON2I);
        
        $default = $hash->hash("Foo");
        
        $this->assertFalse($hash->needsRehash($default));
        
        $hash = new NativePasswordHash(PASSWORD_ARGON2I, ["memory_cost" => 2048]);
        
        $this->assertTrue($hash->needsRehash($default));
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Zoe\Component\Password\Hash\NativePasswordHash::__construct()
     */
    public function testExceptionWhenAlgorithmIsInvalid(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Hash password algorithm given is not handled by PHP for now");
        
        $hash = new NativePasswordHash(5);
    }
    
}
