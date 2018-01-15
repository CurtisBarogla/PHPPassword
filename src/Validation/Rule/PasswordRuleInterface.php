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

/**
 * Process a password over a rule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordRuleInterface
{
    
    /**
     * Comply a password over a rule
     * 
     * @param string $password
     *   Password to check
     * 
     * @return bool
     *   True if the password is complying declared rule. False otherwise
     */
    public function comply(string $password): bool;
    
    /**
     * Get error message if the password is not complying declared rule
     * 
     * @return string
     *   Error message
     */
    public function getError(): string;
    
}
