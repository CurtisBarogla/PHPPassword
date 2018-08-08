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
use Ness\Component\Password\Rotation\Policy\PasswordRotationPolicyInterface;
use Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderInterface;

/**
 * Native implementation of PasswordRotationPolicyManagerInterface.
 * Base on a set of PasswordRotationPolicyInterface implementations
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordRotationPolicyManager implements PasswordRotationPolicyManagerInterface
{
    
    /**
     * Policies registered
     * 
     * @var PasswordRotationPolicyInterface[]|null
     */
    private $policies;
    
    /**
     * Password rotation time provider
     * 
     * @var LastPasswordRotationProviderInterface
     */
    private $provider;
    
    /**
     * Initialize manager
     * 
     * @param LastPasswordRotationProviderInterface $provider
     *   Password rotation time provider 
     */
    public function __construct(LastPasswordRotationProviderInterface $provider)
    {
        $this->provider = $provider;
    }
    
    /**
     * Add a policy into the manager
     * 
     * @param PasswordRotationPolicyInterface $policy
     *   Password rotation policy
     */
    public function addPolicy(PasswordRotationPolicyInterface $policy): void
    {
        $this->policies[] = $policy;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Rotation\PasswordRotationPolicyManagerInterface::shouldRotate()
     */
    public function shouldRotate(UserInterface $user): bool
    {
        if(null === $this->policies || null === $rotation = $this->provider->provide($user))
            return false;
        
        foreach ($this->policies as $policy) {
            if($policy->support($user) && $policy->apply($rotation))
                return true;
            
            continue;
        }
        
        return false;
    }

}
