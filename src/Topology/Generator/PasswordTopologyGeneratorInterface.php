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

namespace Ness\Component\Password\Topology\Generator;

use Ness\Component\Password\Password;
use Ness\Component\Password\Topology\PasswordTopology;
use Ness\Component\Password\Exception\UnsupportedPasswordException;

/**
 * Generate a topology from a password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyGeneratorInterface
{
    
    
    /**
     * Generate a topology from the given password
     * 
     * @param Password $password
     *   Password to generate the topology
     *   
     * @return PasswordTopology
     *   Password topology
     *   
     * @throws UnsupportedPasswordException
     *   When the given password cannot be handled by this generator
     */
    public function generate(Password $password): PasswordTopology;
    
    /**
     * Identify the generator. 
     * Must be unique
     * 
     * @return string
     *   Generator identifier
     */
    public function getIdentifier(): string;
    
}
