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
use Zoe\Component\Password\Topology\PasswordTopologyCollection;

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
    public function load(PasswordTopology $topology, ?int $limit): ?PasswordTopologyCollection
    {
        $identifier = $topology->generatedBy();
        if(!isset($this->topologies[$identifier]) || empty($this->topologies[$identifier]))
            return null;
        
        $collection = new PasswordTopologyCollection();

        $loadables = (null !== $limit) ? \array_slice($this->topologies[$identifier], 0, $limit) : $this->topologies[$identifier];
        foreach ($loadables as $topology) {
            $collection[] = new PasswordTopology($topology, $identifier);
        }
 
        return $collection;
    }
   
}
