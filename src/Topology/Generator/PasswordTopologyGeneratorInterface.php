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

namespace Zoe\Component\Password\Topology\Generator;

use Zoe\Component\Password\Password;
use Zoe\Component\Password\Exception\UnexpectedMethodCallException;
use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * Responsible to generate a formated topology pattern from a password.
 * 
 * @see https://www.korelogic.com/Resources/Presentations/bsidesavl_pathwell_2014-06.pdf
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyGeneratorInterface
{
    
    /**
     * Format a topology from the given password respecting a defined pattern
     * 
     * @param string $password
     *   Password to convert
     *   
     * @return PasswordTopology
     *   Password topology
     *   
     * @throws UnexpectedMethodCallException
     *   If support method has been not already called
     */
    public function format(Password $password): PasswordTopology;
    
    /**
     * Check if the given password can be handled by the generator
     * 
     * @param string $password
     *   Password to check
     * 
     * @return bool
     *   True if the generator can convert the password. False otherwise
     */
    public function support(Password $password): bool;
    
    /**
     * Get the identifier of the generator.
     * It will be linked to a topology after generation and loading process
     * 
     * @return string
     *   Generator identifier
     */
    public function getIdentifier(): string;
    
}
