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

namespace Ness\Component\Password\Rotation;

use Ness\Component\User\UserInterface;

/**
 * Handle checking policies over a user if a password rotation should be applied
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordRotationPolicyManagerInterface
{
        
    /**
     * Check if a password user should be change depending of user state
     * 
     * @param UserInterface $user
     *   User processed
     * 
     * @return bool
     *   True if the password should be changed. False otherwise
     */
    public function shouldRotate(UserInterface $user): bool;
    
}
