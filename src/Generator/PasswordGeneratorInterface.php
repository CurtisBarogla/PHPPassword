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
 * Generate secure password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordGeneratorInterface
{
    
    /**
     * Generate a password of an artitrary length 
     * 
     * @param int $length
     *   Password length
     * 
     * @return Password
     *   Password generated
     */
    public function generate(int $length): Password;
    
}
