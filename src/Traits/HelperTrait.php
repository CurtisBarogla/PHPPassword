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

namespace Ness\Component\Password\Traits;

/**
 * Provide simple helpers to interact with values into the password component
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
trait HelperTrait
{
    
    /**
     * Reassign given base with interpolated values.
     * Interpolated value key MUST respect pattern '{:key:}'
     *
     * @param string $base
     *   Base string to interpolate
     * @param string[] $values
     *   Values to interpolate indexed by the key representing it
     *   
     * @return string
     *   Base interpolated with given values
     */
    protected function interpolate(string $base, array $values): string
    {
        return \str_replace(\array_map(function(string $key): string {
            return "{:{$key}:}";
        }, \array_keys($values)), $values, $base);
    }
    
}
