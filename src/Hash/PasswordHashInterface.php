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

namespace Zoe\Component\Password\Hash;

/**
 * Responsible to hash and validate password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordHashInterface
{
    
    /**
     * Hash a password
     * 
     * @param string $password
     *   Password to hash
     * @param string|null $salt
     *   Salt to apply. Can be null
     * 
     * @return string
     *   Hashed password
     */
    public function hash(string $password, ?string $salt = null): string;
    
    /**
     * Check if a password comply its hashed version
     * 
     * @param string $password
     *   Clear password
     * @param string $hash
     *   Hashed password
     * @param string|null $salt
     *   Salt applied. Can be null
     * 
     * @return bool
     *   True if the password corresponds to its hashed version. False otherwise
     */
    public function isValid(string $password, string $hash, ?string $salt = null): bool;
    
    /**
     * Check if a hash must be rehashed to comply new parameters
     * 
     * @param string $hash
     *   Hash to check
     * 
     * @return bool
     *   True if the hash must be rehashed. False otherwise
     */
    public function needsRehash(string $hash): bool;
    
}
