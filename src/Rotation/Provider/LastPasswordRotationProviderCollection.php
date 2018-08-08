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
 * Try to load the last rotation time from a set a LastPasswordRotationProviderInterface implementations
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class LastPasswordRotationProviderCollection implements LastPasswordRotationProviderInterface
{
    
    /**
     * Provider collection
     * 
     * @var LastPasswordRotationProviderInterface[]|null
     */
    private $providers = null;
    
    /**
     * Register a provider into the collection
     * 
     * @param LastPasswordRotationProviderInterface $provider
     *    Rotation provider
     */
    public function addProvider(LastPasswordRotationProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderInterface::provide()
     */
    public function provide(UserInterface $user): ?\DateTimeImmutable
    {
        if(null === $this->providers)
            return null;
        
        foreach ($this->providers as $provider) {
            if(null !== $rotation = $provider->provide($user))
                return $rotation;
            
            continue;
        }
        
        return null;
    }
    
}
