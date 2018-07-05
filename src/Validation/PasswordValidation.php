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

namespace Ness\Component\Password\Validation;

use Ness\Component\Password\Password;
use Ness\Component\Password\Validation\Rule\PasswordRuleInterface;

/**
 * Native implementation of PasswordValidationInterface.
 * Use a set of PasswordRuleInterface implementations to validate a password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordValidation implements PasswordValidationInterface
{
    
    /**
     * Rules setted
     * 
     * @var PasswordRuleInterface[]
     */
    private $rules;
    
    /**
     * All errors from the rules if the password is not valid
     * 
     * @var string[]|null
     */
    private $errors = null;
    
    /**
     * Add a rule into the validation process
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
     * @see \Ness\Component\Password\Validation\PasswordValidationInterface::comply()
     */
    public function comply(Password $password): bool
    {
        if(null === $this->rules)
            return true;

        // clear if a call has been previously made
        $this->errors = null;
            
        foreach ($this->rules as $rule) {
            if(!$rule->comply($password)) {
                $this->errors[] = $rule->getError();
            }
        }
            
        return null === $this->errors;
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\PasswordValidationInterface::getErrors()
     */
    public function getErrors(): ?array
    {
        return $this->errors;
    }
    
}
