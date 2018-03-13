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

/**
 * Describe a topology from a password and the generator used
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class Topology
{
    
    /**
     * Topology generated from a password
     * 
     * @var string
     */
    private $topology;
    
    /**
     * Password generator identifier
     * 
     * @var string
     */
    private $generator;
    
    /**
     * Initialize a password topology
     * 
     * @param string $topology
     *   Password topology from a password
     * @param string $generator
     *   Generator identifier used for generating the topology
     */
    public function __construct(string $topology, string $generator)
    {
        $this->topology = $topology;
        $this->generator = $generator;
    }
    
    /**
     * Get topology
     * 
     * @return string
     *   Password topology
     */
    public function getTopology(): string
    {
        return $this->topology;
    }
    
    /**
     * Get generator identifier used for generating the password topology
     * 
     * @return string
     *   Password topology generator name
     */
    public function generatedBy(): string
    {
        return $this->generator;
    }
    
}
