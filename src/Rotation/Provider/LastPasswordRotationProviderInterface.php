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

namespace Ness\Component\Password\Rotation\Provider;

use Ness\Component\User\UserInterface;

/**
 * Provide the last time a user password has been changed
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface LastPasswordRotationProviderInterface
{
    
    /**
     * Provide from the given user the last time a password has been changed
     * 
     * @param UserInterface $user
     *   User processed
     * 
     * @return \DateTimeImmutable|null
     *   Last time the password has been changed. Return null if not chaged at all or impossible to provide
     */
    public function provide(UserInterface $user): ?\DateTimeImmutable;
    
}
