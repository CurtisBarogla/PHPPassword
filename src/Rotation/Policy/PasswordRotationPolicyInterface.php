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

namespace Ness\Component\Password\Rotation\Policy;

use Ness\Component\User\UserInterface;

/**
 * Policy applied on a user over its state
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordRotationPolicyInterface
{
    
    /**
     * Apply a rule
     * 
     * @param \DateTimeImmutable $lastRotation
     *   Last time a password rotation has been applied
     * 
     * @return bool
     *   True if the user handled by support in eligible to rotation. False otherwise
     */
    public function apply(\DateTimeImmutable $lastRotation): bool;
    
    /**
     * Check if the user is concerned by this policy
     * 
     * @param UserInterface $user
     *   User to check
     * 
     * @return bool
     *   True if the policy is applicable on the user. False otherwise
     */
    public function support(UserInterface $user): bool;
    
}
