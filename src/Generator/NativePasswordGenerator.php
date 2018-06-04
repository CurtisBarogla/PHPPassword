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

namespace Ness\Component\Password\Generator;

use Ness\Component\Password\Password;

/**
 * Simply generate a password use random_bytes function
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordGenerator implements PasswordGeneratorInterface
{
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Generator\PasswordGeneratorInterface::generate()
     */
    public function generate(int $length): Password
    {
        return new Password(\substr(\bin2hex(\random_bytes($length)), -$length, $length));
    }
    
}
