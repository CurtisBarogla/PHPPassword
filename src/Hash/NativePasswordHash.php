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
 * Simple wrapper around password_* native php functions
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordHash implements PasswordHashInterface
{
    
    /**
     * Algorithm const used by password_*
     * 
     * @var int
     */
    private $algorithm;
    
    /**
     * Options applied to password_*
     * 
     * @var array
     */
    private $options;
    
    /**
     * Initialize hashing
     * 
     * @param int $algorithm
     *   Algorithm used. By default, use Bcrypt
     * @param array|null $options
     *   Options applied. If setted to null, will apply defaults options defined by php
     *   
     * @throws \UnexpectedValueException
     *   When a given options is not handled or given algorithm is invalid
     */
    public function __construct(int $algorithm = PASSWORD_BCRYPT, ?array $options = null)
    {
        $this->algorithm = $algorithm;
        $this->options = $options;
        
        $this->validateAlgorithm();
        $this->validateOptions();
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::hash()
     */
    public function hash(Password $password, ?string $salt = null): string
    {
        $hash = password_hash($password->get(), $this->algorithm, $this->options);
        
        if(false === $hash || null === $hash)
            throw new HashErrorException("Impossible to hash password");
        
        return $hash;
    }    

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::verify()
     */
    public function verify(Password $password, string $hash, ?string $salt = null): bool
    {
        return \password_verify($password->get(), $hash);
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::needsRehash()
     */
    public function needsRehash(string $hash, ?string $salt = null): bool
    {
        return \password_needs_rehash($hash, $this->algorithm, $this->options);
    }
    
    /**
     * Validate setted algorithm
     * 
     * @throws \UnexpectedValueException
     *   When given algorithm is not handled
     */
    private function validateAlgorithm(): void
    {
        if(! (\defined("PASSWORD_ARGON2I") ? \in_array($this->algorithm, [PASSWORD_BCRYPT, PASSWORD_ARGON2I]) : $this->algorithm === PASSWORD_BCRYPT) ) {
            throw new \UnexpectedValueException("Given algorithm to NativePasswordHash is not handled by your PHP version");
        }
    }
    
    /**
     * Validate options and set defaults ones when undefined
     * 
     * @throws \UnexpectedValueException
     *   When an option is invalid
     */
    private function validateOptions(): void
    {
        $options = [
            PASSWORD_BCRYPT     =>  [
                "cost"              =>  PASSWORD_BCRYPT_DEFAULT_COST
            ]
        ];
        
        if(\defined("PASSWORD_ARGON2I")) {
            $options[PASSWORD_ARGON2I] = [
                "memory_cost"       =>  PASSWORD_ARGON2_DEFAULT_MEMORY_COST,
                "time_cost"         =>  PASSWORD_ARGON2_DEFAULT_TIME_COST,
                "threads"           =>  PASSWORD_ARGON2_DEFAULT_THREADS
            ];
        }
        
        if(null === $this->options) {
            $this->options = $options[$this->algorithm];
            
            return;
        }
        
        $this->options = \array_replace($options[$this->algorithm], $this->options);
        
        if($invalids = \array_diff_key($this->options, $options[$this->algorithm]))
            throw new \UnexpectedValueException(\sprintf("This options '%s' is/are invalid considering given algorithm. Options for given algorithm are : '%s'",
                \implode(", ", \array_keys($invalids)),
                \implode(", ", \array_keys($options[$this->algorithm]))));
    }

}
