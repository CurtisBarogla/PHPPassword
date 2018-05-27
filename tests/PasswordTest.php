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

namespace NessTest\Component\Password;

use Ness\Component\Password\Password;

/**
 * Password testcase
 * 
 * @see \Ness\Component\Password\Password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Password::get()
     */
    public function testGet(): void
    {
        $password = new Password("Foo");
        
        $this->assertSame("Foo", $password->get());
    }
    
    /**
     * @see \Ness\Component\Password\Password::getExploded()
     */
    public function testGetExploded(): void
    {
        $password = new Password("FooBar|é");
        
        $this->assertSame(["F", "o", "o", "B", "a", "r", "|", "é"], $password->getExploded());
    }
    
    /**
     * @see \Ness\Component\Password\Password::count()
     */
    public function testCount(): void
    {
        $password = new Password("FooBar|é");
        
        $this->assertSame(8, \count($password));
    }
    
}
