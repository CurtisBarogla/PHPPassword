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

namespace Ness\Component\Password;

/**
 * Manage generation and validation of password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordManagerInterface
{
    
    /**
     * Generate a password
     * 
     * @param int $length
     *   Password length required
     * 
     * @return Password
     *   A password with an arbitrary value
     */
    public function generate(int $length): Password;
    
    /**
     * Get a hash of a password
     * 
     * @param Password $password
     *   Password to hash
     * @param string|null $salt
     *   Salt to apply. Can be null
     *   
     * @return string
     *   Hashed password
     */
    public function hash(Password $password, ?string $salt = null): string;
    
    /**
     * Check a password to its hash
     * 
     * @param Password $password
     *   Password to check
     * @param string $hash
     *   Hashed password representation
     * @param string|null $salt
     *   Salt applied
     * 
     * @return bool
     *   True if the password corresponds the given hash. False otherwise 
     */
    public function isValid(Password $password, string $hash, ?string $salt = null): bool;
    
    /**
     * Check a password security over arbitrary rules
     * 
     * @param Password $password
     *   Password to check
     * 
     * @return bool
     *   True if the password is considered secure. False otherwise
     */
    public function isSecure(Password $password): bool;
    
}
