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

namespace Ness\Component\Password\Topology;

use Ness\Component\Password\Password;
use Ness\Component\Password\Exception\UnsupportedPasswordException;

/**
 * Manage generation and validation of password topology over password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyManagerInterface
{
    
    /**
     * Generate a topology over a password
     * 
     * @param Password $password
     *   Password which the topology must be generated
     * 
     * @return PasswordTopology
     *   A password topology
     *   
     * @throws UnsupportedPasswordException
     *   When a topology cannot be generated
     */
    public function generate(Password $password): PasswordTopology;
    
    /**
     * Check if a topology is considered secure
     * 
     * @param PasswordTopology $topology
     *   Topology to check
     * 
     * @return bool
     *   True if the topology can be considered secure. False otherwise
     */
    public function isSecure(PasswordTopology $topology): bool;
    
}
