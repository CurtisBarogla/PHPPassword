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
use Ness\Component\Password\Topology\PasswordTopologyManagerInterface;
use Ness\Component\Password\Exception\UnsupportedPasswordException;

/**
 * Restrict most common topology patterns over a password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedPasswordTopologyRule extends AbstractPasswordRule
{
    
    /**
     * Topology manager
     * 
     * @var PasswordTopologyManagerInterface
     */
    private $manager;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message to display
     * @param PasswordTopologyManagerInterface $manager
     *   Topology manager
     */
    public function __construct(string $error, PasswordTopologyManagerInterface $manager)
    {
        parent::__construct($error);
        $this->manager = $manager;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        try {
            return $this->manager->isSecure($this->manager->generate($password));              
        } catch (UnsupportedPasswordException $e) {
            return false;
        }
    }
    
}
