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

namespace Ness\Component\Password\Hash;

use Ness\Component\Password\Password;
use Ness\Component\Password\Exception\HashErrorException;

/**
 * Handle password hashing
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordHashInterface
{
    
    /**
     * Hash a password
     * 
     * @param Password $password
     *   Password to hash
     * @param string|null $salt
     *   Salt to apply
     * 
     * @return string
     *   Hashed password
     *   
     * @throws HashErrorException
     *   When given password cannot be hashed
     */
    public function hash(Password $password, ?string $salt = null): string;
    
    /**
     * Check if the password corresponds to the given hash
     * 
     * @param Password $password
     *   Password to validate
     * @param string $hash
     *   Hash to compare
     * @param string|null $salt
     *   Salt applied
     * 
     * @return bool
     *   True if the given password corresponds to the given hash
     *   
     * @throws HashErrorException
     *   When an error happen during comparison
     */
    public function verify(Password $password, string $hash, ?string $salt = null): bool;
    
    /**
     * Check if the hash must be reshashed to correspond a new configuration
     * 
     * @param string $hash
     *   Hash
     * @param string|null $salt
     *   Salt applied
     *   
     * @return bool
     *   True if the hash must be rehashed to comply new parameters. False otherwise
     *   
     * @throws HashErrorException
     *   When an given hash cannot be verified
     */
    public function needsRehash(string $hash, ?string $salt = null): bool;
    
}
