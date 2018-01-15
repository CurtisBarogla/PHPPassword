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

namespace Zoe\Component\Password\Validation;

use Zoe\Component\Password\Validation\Rule\PasswordRuleInterface;

/**
 * This implementation validate a password over PasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordValidation implements PasswordValidationInterface
{
    
    /**
     * All rules assigned 
     * 
     * @var PasswordRuleInterface[]
     */
    private $rules;
    
    /**
     * All errors found over all rules defined
     * 
     * @var array|null
     */
    private $errors = null;
    
    /**
     * Add a rule to the validation process
     * 
     * @param PasswordRuleInterface $rule
     *   Password rule
     */
    public function addRule(PasswordRuleInterface $rule): void
    {
        $this->rules[] = $rule;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\PasswordValidationInterface::comply()
     */
    public function comply(string $password): bool
    {
        if(null === $this->rules)
            return true;
        
        $valid = true;
        foreach ($this->rules as $rule) {
            if(!$rule->comply($password)) {
                $this->errors[] = $rule->getError();
                $valid = false;
            }
        }
        
        return $valid;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\PasswordValidationInterface::getErrors()
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }

}
