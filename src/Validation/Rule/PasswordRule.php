<?php
//StrictType
declare(strict_types = 1);

/*
 * Zoe
 * Password component
 *
 * Author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */

namespace Zoe\Component\Password\Validation\Rule;

/**
 * Base for all PasswordRule
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class PasswordRule implements PasswordRuleInterface
{
    
    /**
     * Error message when rule fails to comply a password
     * 
     * @var string
     */
    protected $error;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message when rule fails to comply a password
     */
    public function __construct(string $error)
    {
        $this->error = $error;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRuleInterface::getError()
     */
    public function getError(): string
    {
        return $this->error;
    }
    
    /**
     * Interpolate value respecting {:tointerpolate:} pattern into a string
     * 
     * @param array $keys
     *   Keys value to interpolate with
     * @param array $by
     *   Values to set
     * @param string $into
     *   String to interpolate
     * 
     * @return string
     *   String interpolated
     */
    protected function interpolate(array $keys, array $by, string $into): string
    {
        $keys = \array_map(function(string $key): string {
            return "{:{$key}:}";
        }, $keys);
        
        return \str_replace($keys, $by, $into);
    }
    
}
