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

use Ness\Component\Password\Generator\PasswordGeneratorInterface;
use Ness\Component\Password\Validation\PasswordValidationInterface;
use Ness\Component\Password\Hash\PasswordHashInterface;

/**
 * Basic implementation of PasswordManager. Acts as a proxy over all crucials methods of the password component
 * This implementation also implements PasswordGeneratorInterface, PasswordValidationInterface and PasswordHashInterface
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordManager implements PasswordManagerInterface, PasswordGeneratorInterface, PasswordValidationInterface, PasswordHashInterface
{

    /**
     * Password generator
     * 
     * @var PasswordGeneratorInterface
     */
    private $generator;
    
    /**
     * Password validator
     * 
     * @var PasswordValidationInterface
     */
    private $validator;
    
    /**
     * Password hashing
     * 
     * @var PasswordHashInterface
     */
    private $hash;
    
    /**
     * Initialiaze password manager
     * 
     * @param PasswordGeneratorInterface $generator
     *   Password generator
     * @param PasswordValidationInterface $validator
     *   Password validator
     * @param PasswordHashInterface $hash
     *   Password hashing
     */
    public function __construct(PasswordGeneratorInterface $generator, PasswordValidationInterface $validator, PasswordHashInterface $hash)
    {
        $this->generator = $generator;
        $this->validator = $validator;
        $this->hash = $hash;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Generator\PasswordGeneratorInterface::generate()
     */
    public function generate(int $length): Password
    {
        return $this->generator->generate($length);
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\PasswordManagerInterface::hash()
     */
    public function hash(Password $password, ?string $salt = null): string
    {
        return $this->hash->hash($password, $salt);
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\PasswordManagerInterface::isValid()
     */
    public function isValid(Password $password, string $hash, ?string $salt = null): bool
    {
        return $this->hash->verify($password, $hash, $salt);
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\PasswordManagerInterface::isSecure()
     */
    public function isSecure(Password $password): bool
    {
        return $this->validator->comply($password);
    }    
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\PasswordValidationInterface::comply()
     */
    public function comply(Password $password): bool
    {
        return $this->validator->comply($password);
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\PasswordValidationInterface::getErrors()
     */
    public function getErrors(): ?array
    {
        return $this->validator->getErrors();
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::needsRehash()
     */
    public function needsRehash(string $hash, ?string $salt = null): bool
    {
        return $this->hash->needsRehash($hash, $salt);
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::verify()
     */
    public function verify(Password $password, string $hash, ?string $salt = null): bool
    {
        return $this->hash->verify($password, $hash, $salt);
    }
    
}
