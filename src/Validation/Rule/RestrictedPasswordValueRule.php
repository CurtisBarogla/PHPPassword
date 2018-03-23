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

namespace Zoe\Component\Password\Validation\Rule;

use Zoe\Component\Password\Password;

/**
 * Restrict a list of common passwords
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedPasswordValueRule extends PasswordRule
{
    
    /**
     * Password values restricted
     * 
     * @var string[]
     */
    private $restrictedPasswords;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message when rule fails to comply a password
     * @param array $restrictedPasswords
     *   A list of restricted password values
     */
    public function __construct(string $error, array $restrictedPasswords)
    {
        $this->restrictedPasswords = $restrictedPasswords;
        parent::__construct($error);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        return !\in_array($password->getValue(), $this->restrictedPasswords);
    }

}
