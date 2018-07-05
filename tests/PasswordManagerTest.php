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

use Ness\Component\Password\PasswordManager;
use Ness\Component\Password\Generator\PasswordGeneratorInterface;
use Ness\Component\Password\Validation\PasswordValidationInterface;
use Ness\Component\Password\Hash\PasswordHashInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Ness\Component\Password\Password;

/**
 * PasswordManager testcase
 * 
 * @see \Ness\Component\Password\PasswordManager
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordManagerTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\PasswordManager::generate()
     */
    public function testGenerate(): void
    {
        $action = function(MockObject $generator, MockObject $validator, MockObject $hash): void {
            $generator->expects($this->once())->method("generate")->with(3)->will($this->returnValue(new Password("Foo")));   
        };
        
        $manager = $this->getInitializedManager($action);
        
        $this->assertSame("Foo", $manager->generate(3)->get());
    }
    
    /**
     * @see \Ness\Component\Password\PasswordManager::hash()
     */
    public function testHash(): void
    {
        $password = new Password("Foo");
        $action = function(MockObject $generator, MockObject $validator, MockObject $hash) use ($password): void {
            $hash->expects($this->once())->method("hash")->with($password)->will($this->returnValue("Oof"));
        };
        
        $manager = $this->getInitializedManager($action);
        
        $this->assertSame("Oof", $manager->hash($password));
    }
    
    /**
     * @see \Ness\Component\Password\PasswordManager::isValid()
     * @see \Ness\Component\Password\PasswordManager::verify()
     */
    public function testIsValidAndVerify(): void
    {
        $password = new Password("Foo");
        $action = function(MockObject $generator, MockObject $validator, MockObject $hash) use ($password): void {
            $hash->expects($this->exactly(2))->method("verify")->with($password, "Oof")->will($this->returnValue(true));
        };
        
        $manager = $this->getInitializedManager($action);
        
        $this->assertTrue($manager->isValid($password, "Oof"));
        $this->assertTrue($manager->verify($password, "Oof"));
    }
    
    /**
     * @see \Ness\Component\Password\PasswordManager::isSecure()
     * @see \Ness\Component\Password\PasswordManager::comply()
     */
    public function testIsSecureAndComply(): void
    {
        $password = new Password("Foo");
        $action = function(MockObject $generator, MockObject $validator, MockObject $hash) use ($password): void {
            $validator->expects($this->exactly(2))->method("comply")->with($password)->will($this->returnValue(true));
        };
        
        $manager = $this->getInitializedManager($action);
        
        $this->assertTrue($manager->isSecure($password));
        $this->assertTrue($manager->comply($password));
    }
    
    /**
     * @see \Ness\Component\Password\PasswordManager::getErrors()
     */
    public function testGetErrors(): void
    {
        $action = function(MockObject $generator, MockObject $validator, MockObject $hash): void {
            $validator->expects($this->exactly(2))->method("getErrors")->will($this->onConsecutiveCalls(null, ["Foo", "Bar"]));
        };
        
        $manager = $this->getInitializedManager($action);
        
        $this->assertNull($manager->getErrors());
        $this->assertSame(["Foo", "Bar"], $manager->getErrors());
    }
    
    /**
     * @see \Ness\Component\Password\PasswordManager::needsRehash()
     */
    public function testNeedsRehash(): void
    {
        $action = function(MockObject $generator, MockObject $validator, MockObject $hash): void {
            $hash->expects($this->once())->method("needsRehash")->with("Oof")->will($this->returnValue(false));
        };
        
        $manager = $this->getInitializedManager($action);
        
        $this->assertFalse($manager->needsRehash("Oof"));
    }
    
    /**
     * Get an initialized password manager with component mocked setted into it
     * 
     * @param \Closure|null $action
     *   Action to perform on the mocked components. Takes as first parameter the generator, as second the validator and the hash
     *   
     * @return PasswordManager
     *   Initializer password manager
     */
    private function getInitializedManager(?\Closure $action = null): PasswordManager
    {
        $generator = $this->getMockBuilder(PasswordGeneratorInterface::class)->getMock();
        $validator = $this->getMockBuilder(PasswordValidationInterface::class)->getMock();
        $hash = $this->getMockBuilder(PasswordHashInterface::class)->getMock();
        
        if(null !== $action)
            $action->call($this, $generator, $validator, $hash);
        
        return new PasswordManager($generator, $validator, $hash);
    }
    
}
