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
 * Limit a character in a row for a password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CharRowLimitPasswordRule extends PasswordRule
{   
    
    /**
     * Max same character allowed in a row
     * 
     * @var int
     */
    private $max;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error to display. ( use {:char:} to display repeated char and {:max:} to display max allowed chars in a row 
     * @param int $max
     *   Max same character allowed in a row (setted to 3 by default)
     */
    public function __construct(string $error, int $max = 3)
    {
        $this->max = $max;
        parent::__construct($error);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(string $password): bool
    {
        $password = preg_split('//u', $password, 0, PREG_SPLIT_NO_EMPTY);
        $length = \count($password);
        
        for ($i = 0; $i < $length; $i++) {
            if($i < $this->max - 1) {               
                continue;
            }
            $found = 1;
            $current = $this->max - 1;
            while ($password[$i - $current] === $password[$i] && $found < $this->max) {
                $found++;
                $current--;
            }
            if($found >= $this->max) {
                $this->error = $this->interpolate(["max", "char"], [(string) $this->max, $password[$i]], $this->error);
                
                return false;
            }
        }
        
        return true;
    }

}
