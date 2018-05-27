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
 * Deny password values from a list
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedValuePasswordRule extends AbstractPasswordRule
{
    
    /**
     * Restricted values
     * 
     * @var string[]
     */
    private $restricted;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message to display.
     * @param array $restricted
     *   Set of restricted password values
     */
    public function __construct(string $error, array $restricted)
    {
        parent::__construct($error);
        $this->restricted = $restricted;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        return !\in_array($password->get(), $this->restricted);
    }

}
