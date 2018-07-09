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

namespace Ness\Component\Password\Validation\Rule;

use function Ness\Component\Password\interpolate;

/**
 * Common to all password rules
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class AbstractPasswordRule implements PasswordRuleInterface
{
    
    /**
     * Error message
     * 
     * @var string
     */
    protected $error;
    
    /**
     * Basic constructor to initialize a rule.
     * Implementations using this base can interpolate values based on keys respecting pattern {:key:} via interpolate method
     * 
     * @param string $error
     *   Error message to display
     */
    public function __construct(string $error)
    {
        $this->error = $error;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\Rule\PasswordRuleInterface::getError()
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Reassign error message with interpolated values.
     * Interpolated value key MUST respect pattern '{:key:}'
     * 
     * @param array[string] $values
     *   Values to interpolate indexed by the key representing it 
     */
    protected function interpolate(array $values): void
    {
        $this->error = interpolate($this->error, $values);
    }
    
}
