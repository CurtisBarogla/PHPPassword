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

use Zoe\Component\Password\Password;

/**
 * Check if a password has a min/max count of characters
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class MinMaxPasswordRule extends PasswordRule
{
    
    /**
     * Max characters allowed
     * 
     * @var int
     */
    private $max;
    
    /**
     * Min characters allowed
     * 
     * @var int
     */
    private $min;
    
    /**
     * Error message when max characters allowed is reached
     * 
     * @var string
     */
    private $errorMax;
    
    /**
     * Error message when min characters allowed is reached
     *
     * @var string
     */
    private $errorMin;
    
    /**
     * Initialize rule
     * 
     * @param string $errorMin
     *   Error message when min characters count is not reached (use {:min:} to display min chars required)
     * @param string $errorMax
     *   Error message when max characters count is reached (use {:max:} to display max chars allowed)
     * @param int $min
     *   Min characters required (setted to 10 by default)
     * @param int $max
     *   Max characters allowed (setted to 128 by default)
     * 
     * @throws \LogicException
     *   When callable is invalid
     */
    public function __construct(string $errorMin, string $errorMax, int $min = 10, int $max = 128, $countCallable = null)
    {
        if($min >= $max)
            throw new \LogicException(\sprintf("Min characters required cannot be greater or equal than max chars allowed. '%d' min given - '%d' max given",
                $min,
                $max));
        
        $this->errorMin = $errorMin;
        $this->errorMax = $errorMax;
        $this->min = $min;
        $this->max = $max;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        $length = \count($password);
        
        if(($tooShort = $length < $this->min) || $length > $this->max) {
            $this->error = ($tooShort) ? 
                                $this->interpolate(["min"], [(string) $this->min], $this->errorMin) : 
                                $this->interpolate(["max"], [(string) $this->max], $this->errorMax);
            
            return false;
        }

        return true;
    }

}
