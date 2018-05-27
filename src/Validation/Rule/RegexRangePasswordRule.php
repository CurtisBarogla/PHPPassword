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
use Ness\Component\Password\RegexRangeAwareInterface;
use Ness\Component\Password\Traits\RegexRangeAwareTrait;

/**
 * Validation over a regex range
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangePasswordRule extends AbstractPasswordRule implements RegexRangeAwareInterface
{
    
    use RegexRangeAwareTrait;
    
    /**
     * Ranges required to validate the password
     * 
     * @var int
     */
    private $required;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message to display. <br />
     *   Use <br /> 
     *   {:"range_identifier"_min:} (min required for given range) <br />
     *   {:"range_identifier"_max:} (max allowed for given range) <br />
     *   {:required:} to display number of passed ranges required <br />
     *   {:ranges:} to display a count of all ranges declared
     * @param int|null $required
     *   Ranges required to validate the password. If setted to null, will automatically required all setted ranges
     */
    public function __construct(string $error, ?int $required = null)
    {
        parent::__construct($error);
        $this->required = $required;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     * 
     * @throws \LogicException
     *   When required range is superior than declared into setted RegexRange
     */
    public function comply(Password $password): bool
    {
        $range = $this->getRange();
        $result = $range->preg($password->get());
        $count = \count($range);
        
        if(null === $this->required)
            $this->required = $count;
        else {
            if($count < $this->required) {
                throw new \LogicException("Setted RegexRange has '{$count}' ranges registered and rule is requiring '{$this->required}'");
            }
        }
        
        if(null === $result || $result < $this->required) {
            $this->interpolate(["required" => $this->required, "ranges" => $count]);
            foreach ($range->getRanges() as $identifier => $range) {
                $this->interpolate(["{$identifier}_min" => (string)$range["min"], "{$identifier}_max" => (string)$range["max"]]);
            }
            
            return false;
        }
        
        return true;
    }
    
}
