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

namespace Ness\Component\Password\Hash {
    
    global $fail;
    
    /**
     * Initialize error on password_hash
     * 
     * @param bool $error
     *   Set to true when password_hash must return false
     */
    function init(bool $error)
    {
        global $fail;
        $fail = $error;
    }
    
    /**
     * Overwrite password_hash
     * 
     * @param string $password
     *   Password value
     * @param int $algo
     *   Algorithm used
     * @param array $options
     *   Options applied
     * 
     * @return bool|string
     *   False or a hash
     */
    function password_hash($password, $algo, $options)
    {
        global $fail;
        
        return ($fail) ? false : \password_hash($password, $algo, $options);
    }
    
};

namespace NessTest\Component\Password\Hash {
    
    use NessTest\Component\Password\PasswordTestCase;
    use Ness\Component\Password\Hash\NativePasswordHash;
    use function Ness\Component\Password\Hash\init;
    use Ness\Component\Password\Password;
    use Ness\Component\Password\Exception\HashErrorException;
                                                                    
    /**
     * NativePasswordHash testcase
     * 
     * @see \Ness\Component\Password\Hash\NativePasswordHash
     * 
     * @author CurtisBarogla <curtis_barogla@outlook.fr>
     *
     */
    class NativePasswordHashTest extends PasswordTestCase
    {
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::__construct()
         */
        public function testOptionsOverwriting(): void
        {
            $hash = new NativePasswordHash();

            $this->assertSame(["cost" => 10], $this->extractOptions($hash));
            
            $hash = new NativePasswordHash(PASSWORD_BCRYPT, ["cost" => 20]);
            
            $this->assertSame(["cost" => 20], $this->extractOptions($hash));
            
            if(\defined("PASSWORD_ARGON2I")) {
                $hash = new NativePasswordHash(PASSWORD_ARGON2I, ["threads" => 15]);
                
                $this->assertSame([
                    "memory_cost"       =>  PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                    "time_cost"         =>  PASSWORD_ARGON2_DEFAULT_TIME_COST,
                    "threads"           =>  15
                ], $this->extractOptions($hash));
            }
        }
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::hash()
         */
        public function testHash(): void
        {
            $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
            $password->expects($this->once())->method("get")->will($this->returnValue("Foo"));
            
            init(false);
            $hash = new NativePasswordHash();
            
            $this->assertNotFalse($hash->hash($password));
        }
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::verify()
         */
        public function testVerify(): void
        {
            $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
            $password->expects($this->exactly(3))->method("get")->will($this->onConsecutiveCalls("Foo", "Foo", "Bar"));
            
            init(false);
            $hash = new NativePasswordHash();
            $hashed = $hash->hash($password);
            
            $this->assertTrue($hash->verify($password, $hashed));
            $this->assertFalse($hash->verify($password, $hashed));
        }
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::needsRehash()
         */
        public function testNeedsRehash(): void
        {
            $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
            $password->expects($this->once())->method("get")->will($this->returnValue("Foo"));
            
            init(false);
            $hash = new NativePasswordHash();
            $hashed = $hash->hash($password);
            
            $this->assertFalse($hash->needsRehash($hashed));
            
            $hash = new NativePasswordHash(PASSWORD_BCRYPT, ["cost" => 9]);
            $this->assertTrue($hash->needsRehash($hashed));
            
            $hash = new NativePasswordHash(PASSWORD_ARGON2I);
            $this->assertTrue($hash->needsRehash($hashed));
        }
        
                        /**_____EXCEPTIONS_____**/
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::hash()
         */
        public function testExceptionHashWhenHashPasswordHashFailed(): void
        {
            $this->expectException(HashErrorException::class);
            $this->expectExceptionMessage("Impossible to hash password");
            
            $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
            $password->expects($this->once())->method("get")->will($this->returnValue("Foo"));
            
            init(true);
            $hash = new NativePasswordHash();
            
            $hash->hash($password);
        }
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::__construct()
         */
        public function testExceptionWhenANonHandledAlgorithmIsGiven(): void
        {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage("Given algorithm to NativePasswordHash is not handled by your PHP version");
            
            $hash = new NativePasswordHash(3);
        }
        
        /**
         * @see \Ness\Component\Password\Hash\NativePasswordHash::__construct()
         */
        public function testExceptionWhenAnInvalidOptionIsGiven(): void
        {
            $this->expectException(\UnexpectedValueException::class);
            $this->expectExceptionMessage("This options 'foo' is/are invalid considering given algorithm. Options for given algorithm are : 'cost'");
            
            $hash = new NativePasswordHash(PASSWORD_BCRYPT, ["foo" => "bar"]);
        }
        
        /**
         * Extract options array from an instance of NativePasswordHash
         * 
         * @param NativePasswordHash $hash
         *   Instance
         * 
         * @return array
         *   Options setted
         */
        private function extractOptions(NativePasswordHash $hash): array
        {
            $reflection = new \ReflectionClass($hash);
            
            $property = $reflection->getProperty("options");
            $property->setAccessible(true);
            
            return $property->getValue($hash);
        }
    }
}
