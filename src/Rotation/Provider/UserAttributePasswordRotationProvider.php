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
 * Use a user attribute to provide the last rotation
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UserAttributePasswordRotationProvider implements LastPasswordRotationProviderInterface
{
    
    /**
     * Flag for setting the attribute
     * 
     * @var string
     */
    public const USER_ATTRIBUTE_ROTATION_FLAG = "_last_password_rotation_";
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderInterface::provide()
     */
    public function provide(UserInterface $user): ?\DateTimeImmutable
    {
        if(null === $rotation = $user->getAttribute(self::USER_ATTRIBUTE_ROTATION_FLAG))
            return null;
        
        if($rotation instanceof \DateTimeImmutable)
            return $rotation;
        
        if($rotation instanceof \DateTime)
            return \DateTimeImmutable::createFromMutable($rotation);
        
        try {
            // assume valid format
            return new \DateTimeImmutable($rotation);            
        } catch (\Exception $e) {
            // assume timestamp
            return (false !== $rotation = \DateTimeImmutable::createFromFormat('U', (string) $rotation)) ? $rotation : null;
        }
    }
    
}
