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
use Ness\Component\Authentication\User\AuthenticatedUserInterface;

/**
 * Policy applied on a root user
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RootUserPasswordRotationPolicy extends DateIntervalPasswordRotationPolicy
{
    
    /**
     * Initialize policy
     * 
     * @param \DateInterval $interval
     *   Interval which the password should be changed on root users. By default setted to 90 days
     */
    public function __construct(?\DateInterval $interval = null)
    {
        parent::__construct($interval ?? new \DateInterval("P90D"));
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Rotation\Policy\PasswordRotationPolicyInterface::support()
     */
    public function support(UserInterface $user): bool
    {
        return $user instanceof AuthenticatedUserInterface && $user->isRoot();
    }

}
