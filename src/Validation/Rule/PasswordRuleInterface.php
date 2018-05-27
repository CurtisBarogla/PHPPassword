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

namespace Ness\Component\Password\Validation\Rule;

use Ness\Component\Password\Password;

/**
 * Apply a rule over a password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordRuleInterface
{
    
    /**
     * Comply the password to the declared rule
     * 
     * @param Password $password
     *   Password to check
     * 
     * @return bool
     *   True if the password is compliant. False otherwise
     */
    public function comply(Password $password): bool;
    
    /**
     * Get the error message when comply returns false
     * 
     * @return string
     *   Error message
     */
    public function getError(): string;
    
}
