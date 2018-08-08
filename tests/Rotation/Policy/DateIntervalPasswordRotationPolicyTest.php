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

namespace NessTest\Component\Password\Rotation\Policy;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Rotation\Policy\DateIntervalPasswordRotationPolicy;

/**
 * Common to all tests implying usage of DateInterval
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class DateIntervalPasswordRotationPolicyTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Rotation\Policy\DateIntervalPasswordRotationPolicy::apply()
     */
    public function testApply(): void
    {
        $policy = $this->getPolicy();
    }
    
    /**
     * Identify the tested policy
     * 
     * @return DateIntervalPasswordRotationPolicy
     *   Password rotation policy
     */
    abstract protected function getPolicy(): DateIntervalPasswordRotationPolicy;
    
}
