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

use Ness\Component\Password\Password;

/**
 * Simply check if a password character count is in a specified min-max range
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class MinMaxPasswordLengthRule extends AbstractPasswordRule
{
    
    /**
     * Minimun of characters required
     * 
     * @var int
     */
    private $min;
    
    /**
     * Maximum of characters allowed
     * 
     * @var int
     */
    private $max;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message to display. <br />
     *   Use {:min:} (min required), {:max:} (max allowed), {:current:} (current characters count) placeholders to display informations
     * @param int $min
     *   Minimum of characters required - Default 10
     * @param int $max
     *   Maximum of characters allowed - Default 128
     */
    public function __construct(string $error, int $min = 10, int $max = 128)
    {
        parent::__construct($error);
        $this->min = $min;
        $this->max = $max;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        $length = \count($password);
        
        if($length < $this->min || $length > $this->max) {
            $this->interpolate(["min" => $this->min, "max" => $this->max, "current" => $length]);
            
            return false;
        }
        
        return true;
    }

}
