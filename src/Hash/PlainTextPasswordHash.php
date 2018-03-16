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
 * Do nothing
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PlainTextPasswordHash implements PasswordHashInterface
{
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Hash\PasswordHashInterface::hash()
     */
    public function hash(Password $password, ?string $salt = null): string
    {
        return $password->getValue();
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Hash\PasswordHashInterface::isValid()
     */
    public function isValid(Password $password, string $hash, ?string $salt = null): bool
    {
        return $password->getValue() === $hash;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Hash\PasswordHashInterface::needsRehash()
     */
    public function needsRehash(string $hash): bool
    {
        return false;
    }

}
