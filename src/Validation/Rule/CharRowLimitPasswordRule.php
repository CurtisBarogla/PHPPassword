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
 * Limit identical characters in row
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class CharRowLimitPasswordRule extends AbstractPasswordRule
{
    
    /**
     * Limit
     * 
     * @var int
     */
    private $limit;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message. <br />
     *   Use {:limit:} (limit setted), {:char:} (character repeated) to display informations
     * @param int $limit
     *   Max same characters allowed in a row - Default to 2
     */
    public function __construct(string $error, int $limit = 2)
    {
        parent::__construct($error);
        $this->limit = $limit;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        foreach ($exploded = $password->getExploded() as $index => $character) {
            if($index < ($limit = $this->limit) )
                continue;
            $same = 0;
            for ($i = $limit; $i > 0; $i--) {
                if($exploded[$index] === $exploded[$index - $i])
                    $same++;
                
                if($same === $this->limit) {
                    $this->interpolate(["limit" => $this->limit, "char" => $exploded[$index]]);
                    
                    return false;
                }
            }
        }
        
        return true;
    }

    
}
