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

namespace ZoeTest\Component\Password\Fixture;

use Zoe\Component\Password\Validation\Rule\PasswordRule;

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
    public function comply(string $password): bool
    {
        return true;
    }

}
