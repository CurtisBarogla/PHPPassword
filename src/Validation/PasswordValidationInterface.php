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

/**
 * Responsible to validate a password over some arbitrary rules
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordValidationInterface
{
    
    /**
     * Check if a password comply a set of rules
     * 
     * @param string $password
     *   Password to validate
     * 
     * @return bool
     *   True if the password is complying all rules defined. False otherwise
     */
    public function comply(string $password): bool;
    
    /**
     * Get all errors for all rules that declare password not valid
     * 
     * @return array|null
     *   All errors or null if no error has been found
     */
    public function getErrors(): ?array;
    
}
