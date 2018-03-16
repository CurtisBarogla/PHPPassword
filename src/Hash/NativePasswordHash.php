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

use Zoe\Component\Password\Password;

/**
 * Use functions password_* from php
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordHash implements PasswordHashInterface
{
    
    /**
     * One of the algorithm handled by php
     * 
     * @var int
     */
    private $algorithm;
    
    /**
     * Options applied to hash algorithm
     * 
     * @var array
     */
    private $options;
    
    /**
     * Algorithms handled by php
     * 
     * @var array
     */
    private $algorithms = [PASSWORD_BCRYPT];
    
    /**
     * Initialize password hash
     * 
     * @param int $algorithm
     *   Algorithm used for hashing password. One of defined by php
     * @param array|null $options
     *   Options to apply. Can be null to set defaults options defined by php
     * 
     * @throws \LogicException
     *   When given algorithm is invalid
     */
    public function __construct(int $algorithm = PASSWORD_DEFAULT, ?array $options = null)
    {
        if(\defined("PASSWORD_ARGON2I"))
            $this->algorithms[] = PASSWORD_ARGON2I;
        
        if(!\in_array($algorithm, $this->algorithms))
            throw new \LogicException("Hash password algorithm given is not handled by PHP for now");
        
        $this->algorithm = $algorithm;
        $this->options = (null !== $options) 
                                ? \array_replace($this->getDefaultsOptions($this->algorithm), $options) 
                                : $this->getDefaultsOptions($this->algorithm);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Hash\PasswordHashInterface::hash()
     */
    public function hash(Password $password, ?string $salt = null): string
    {
        return \password_hash($password->getValue(), $this->algorithm, $this->options);
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Hash\PasswordHashInterface::isValid()
     */
    public function isValid(Password $password, string $hash, ?string $salt = null): bool
    {
        return \password_verify($password->getValue(), $hash);
    }    
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Hash\PasswordHashInterface::needsRehash()
     */
    public function needsRehash(string $hash): bool
    {
        return \password_needs_rehash($hash, $this->algorithm, $this->options);
    }
    
    /**
     * Initialize defaults options defined by PHP for an algorithm
     * 
     * @param int $algorithm
     *   Algorithm which to get defaults options
     * 
     * @return array
     *   Defaults options for the given algorithm
     */
    private function getDefaultsOptions(int $algorithm): array
    {
        switch ($algorithm) {
            case PASSWORD_BCRYPT:
                return [
                    "cost"          =>  PASSWORD_BCRYPT_DEFAULT_COST
                ];
            case PASSWORD_ARGON2I:
                return [
                    "memory_cost"   =>  PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                    "time_cost"     =>  PASSWORD_ARGON2_DEFAULT_TIME_COST,
                    "threads"       =>  PASSWORD_ARGON2_DEFAULT_THREADS
                ];
        }
    }
    
}
