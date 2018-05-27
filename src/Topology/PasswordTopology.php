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

/**
 * Represent topology generated from a password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopology
{
    
    /**
     * Password topology
     * 
     * @var string
     */
    private $topology;
    
    /**
     * Identifier to track how the topology has been made
     * 
     * @var string
     */
    private $generatedBy;
    
    /**
     * Initialize a password topology
     * 
     * @param string $topology
     *   Topology
     * @param string $generatedBy
     *   Track how the topology has been made
     */
    public function __construct(string $topology, string $generatedBy)
    {
        $this->topology = $topology;
        $this->generatedBy = $generatedBy;
    }
    
    /**
     * Get topology
     * 
     * @return string
     *   Password topology
     */
    public function get(): string
    {
        return $this->topology;
    }
    
    /**
     * Get how the topology has been made
     * 
     * @return string
     *   Identifier for determined how the topology has been made
     */
    public function generatedBy(): string
    {
        return $this->generatedBy;
    }
    
}
