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

namespace Zoe\Component\Password\Topology;

use Zoe\Component\Password\Password;
use Zoe\Component\Password\Exception\UnexceptedPasswordFormatException;

/**
 * Handle topology generation and validation over Password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyManagerInterface
{
    
    /**
     * Check if a topology (from password) is considered secure
     * 
     * @param PasswordTopology $passwordTopology
     *   Topology to check
     * 
     * @return bool
     *   True if the password is considered secure. False otherwise
     */
    public function isSecure(PasswordTopology $passwordTopology): bool;
    
    /**
     * Generate a topology over a password
     * 
     * @param Password $password
     *   Password which topology must be generated
     * 
     * @return PasswordTopology
     *   Password topology
     *   
     * @throws UnexceptedPasswordFormatException
     *   When topology cannot be generated for whatever reason
     */
    public function generate(Password $password): PasswordTopology;
    
    /**
     * Initialize a set of restricted password topologies identified by a generator identifier
     * 
     * @param string $generatorIdentifier
     *   Password topology generator identifier
     * 
     * @return PasswordTopology[]
     *   Asset of restricted password topologies
     */
    public function getRestrictedPasswordTopologies(string $generatorIdentifier): array;
    
    /**
     * Define a limit amount of password topologies to restrict
     *
     * @param int $limit
     *   Limit of password topologies restricted
     */
    public function setLimit(int $limit): void;
    
}
