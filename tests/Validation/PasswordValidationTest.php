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

namespace NessTest\Component\Password\Validation;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Validation\Rule\PasswordRuleInterface;
use Ness\Component\Password\Validation\PasswordValidation;
use Ness\Component\Password\Password;

/**
 * PasswordValidation testcase
 * 
 * @see \Ness\Component\Password\Validation\PasswordValidation
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordValidationTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Validation\PasswordValidation::addRule()
     */
    public function testAddRule(): void
    {
        $rule = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        
        $validation = new PasswordValidation();
        
        $this->assertNull($validation->addRule($rule));
    }
    
    /**
     * @see \Ness\Component\Password\Validation\PasswordValidation::comply()
     */
    public function testComply(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $ruleOne = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleOne->expects($this->exactly(2))->method("comply")->with($password)->will($this->onConsecutiveCalls(false, true));
        $ruleTwo = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $ruleTwo->expects($this->exactly(2))->method("comply")->with($password)->will($this->onConsecutiveCalls(true, true));
        
        $validation = new PasswordValidation();
        $validation->addRule($ruleOne);
        $validation->addRule($ruleTwo);
        
        $this->assertFalse($validation->comply($password));
        
        $validation = new PasswordValidation();
        $validation->addRule($ruleOne);
        $validation->addRule($ruleTwo);
        
        $this->assertTrue($validation->comply($password));
    }
    
    /**
     * @see \Ness\Component\Password\Validation\PasswordValidation::getErrors()
     */
    public function testGetErrors(): void
    {
        $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
        $rule = $this->getMockBuilder(PasswordRuleInterface::class)->getMock();
        $rule->expects($this->once())->method("comply")->with($password)->will($this->returnValue(false));
        $rule->expects($this->once())->method("getError")->will($this->returnValue("Foo"));
        
        $validation = new PasswordValidation();
        $validation->addRule($rule);
        $validation->comply($password);
        
        $this->assertSame(["Foo"], $validation->getErrors());
        
        $validation = new PasswordValidation();
        $validation->comply($password);
        
        $this->assertNull($validation->getErrors());
    }
    
}
