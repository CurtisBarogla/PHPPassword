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

/**
 * Apply the policy over a DateInterval
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class DateIntervalPasswordRotationPolicy implements PasswordRotationPolicyInterface
{
    
    /**
     * Interval which the password should be changed
     * 
     * @var \DateInterval
     */
    protected $interval;
    
    /**
     * Initialize policy
     * 
     * @param \DateInterval $interval
     *   Interval which the password should be changed  
     */
    public function __construct(\DateInterval $interval)
    {
        $this->interval = $interval;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Rotation\Policy\PasswordRotationPolicyInterface::apply()
     */
    public function apply(\DateTimeImmutable $lastRotation): bool
    {
        return $lastRotation->add($this->interval) < new \DateTime(); 
    }
    
}
