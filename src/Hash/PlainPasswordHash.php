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

/**
 * Basically do nothing except comparing two password strings
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PlainPasswordHash implements PasswordHashInterface
{
        
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::hash()
     */
    public function hash(Password $password, ?string $salt = null): string
    {
        return $password->get();
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::valid()
     */
    public function verify(Password $password, string $hash, ?string $salt = null): bool
    {
        return $password->get() === $hash;
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Hash\PasswordHashInterface::needsRehash()
     */
    public function needsRehash(string $hash, ?string $salt = null): bool
    {
        return false;
    }

}
