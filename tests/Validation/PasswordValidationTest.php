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

namespace ZoeTest\Component\Password\Validation;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Validation\Rule\PasswordRuleInterface;
use Zoe\Component\Password\Validation\PasswordValidation;

/**
 * PasswordValidation testcase
 * 
 * @see \Zoe\Component\Password\Validation\PasswordValidation
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordValidationTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Validation\PasswordValidation::addRule()
     */
    public function testAddRule(): void
    {
        $rule = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        
        $validation = new PasswordValidation();
        
        $this->assertNull($validation->addRule($rule));
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\PasswordValidation::comply()
     * @see \Zoe\Component\Password\Validation\PasswordValidation::getErrors()
     */
    public function testComply(): void
    {
        // no rule defined
        $validation = new PasswordValidation();
        $this->assertTrue($validation->comply("Foo"));
        
        // rules defined
        $password = "Foo";
        $ruleFoo = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleFoo->expects($this->once())->method("comply")->with($password)->will($this->returnValue(true));
        $ruleBar = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleBar->expects($this->once())->method("comply")->with($password)->will($this->returnValue(true));
    
        $validation = new PasswordValidation();
        $validation->addRule($ruleFoo);
        $validation->addRule($ruleBar);
        
        $this->assertTrue($validation->comply("Foo"));
        $this->assertNull($validation->getErrors());
    }
    
    /**
     * @see \Zoe\Component\Password\Validation\PasswordValidation::comply()
     * @see \Zoe\Component\Password\Validation\PasswordValidation::getErrors()
     */
    public function testComplyWithErrors(): void
    {
        $password = "Foo";
        $ruleFoo = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleFoo->expects($this->once())->method("comply")->with($password)->will($this->returnValue(true));
        $ruleBar = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleBar->expects($this->once())->method("comply")->with($password)->will($this->returnValue(false));
        $ruleBar->expects($this->once())->method("getError")->will($this->returnValue("BarErrorMessage"));
        $ruleMoz = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleMoz->expects($this->once())->method("comply")->with($password)->will($this->returnValue(false));
        $ruleMoz->expects($this->once())->method("getError")->will($this->returnValue("MozErrorMessage"));
        
        $validation = new PasswordValidation();
        $validation->addRule($ruleFoo);
        $validation->addRule($ruleBar);
        $validation->addRule($ruleMoz);
        
        $this->assertFalse($validation->comply("Foo"));
        $this->assertSame(["BarErrorMessage", "MozErrorMessage"], $validation->getErrors());
    }
    
}
