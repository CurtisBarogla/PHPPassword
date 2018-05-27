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

/**
 * Responsible to validate a password over a set of criteria
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordValidationInterface
{
    
    /**
     * Comply a password over a set of criteria
     * 
     * @param Password $password
     *   Password to check
     * 
     * @return bool
     *   True if the password if compliant. False otherwise
     */
    public function comply(Password $password): bool;
    
    /**
     * Get all errors from a failed comply call
     * 
     * @return array[string]|null
     *   All errors registered or null if no error
     */
    public function getErrors(): ?array;
    
}
