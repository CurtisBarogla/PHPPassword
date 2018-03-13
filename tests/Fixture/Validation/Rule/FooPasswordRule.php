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

namespace ZoeTest\Component\Password\Fixture\Validation\Rule;

use Zoe\Component\Password\Validation\Rule\PasswordRule;
use Zoe\Component\Password\Password;

/**
 * For testing purpose
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class FooPasswordRule extends PasswordRule
{   
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        return true;
    }

}
