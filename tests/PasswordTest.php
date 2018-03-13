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

namespace ZoeTest\Component\Password;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Password;

/**
 * Password testcase
 * 
 * @see \Zoe\Component\Password\Password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Password::getValue()
     */
    public function testGetValue(): void
    {
        $password = new Password("Foo");
        
        $this->assertSame("Foo", $password->getValue());
    }
    
    /**
     * @see \Zoe\Component\Password\Password::getExplodedPassword()
     */
    public function testGetExplodedPassword(): void
    {
        $password = new Password("\\\\éé@@çç''\"#Fr");
        
        $this->assertSame(["\\", "\\", "é", "é", "@", "@", "ç", "ç", "'", "'", '"', "#", "F", "r"], $password->getExplodedPassword());
    }
    
    /**
     * @see \Zoe\Component\Password\Password::count()
     */
    public function testCount(): void
    {
        $password = new Password("FooBar");
        
        $this->assertSame(6, \count($password));
    }
    
}
