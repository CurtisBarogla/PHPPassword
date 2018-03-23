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

namespace Zoe\Component\Password\Topology\Loader;

use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * Load from a simple array.
 * Can be declared into a simple php file return an array then included into contructor, or directly declared into it.
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyLoader implements PasswordTopologyLoaderInterface
{
    
    /**
     * Password topologies loadables
     * 
     * @var array
     */
    private $topologies;
    
    /**
     * Initialize password loader
     * Given array must follow a specific format : 
     * <pre>
     * [
     *      "generator_identifier"  =>  ["topologies"]
     * ]
     * </pre>
     * 
     * @param array $topologies
     *   Topologies loadables
     */
    public function __construct(array $topologies)
    {
        $this->topologies = $topologies;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(string $generatorIdentifier, int $limit): array
    {
        if(!isset($this->topologies[$generatorIdentifier]))
            return [];
        
        $topologies = [];
        foreach (\array_slice($this->topologies[$generatorIdentifier], 0, $limit) as $topology) {
            $topologies[] = new PasswordTopology($topology, $generatorIdentifier);
        }
 
        return $topologies;
    }
   
}
