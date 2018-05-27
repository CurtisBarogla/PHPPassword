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

namespace Ness\Component\Password\Topology\Loader;

use Ness\Component\Password\Topology\PasswordTopologyCollection;
use Ness\Component\Password\Topology\PasswordTopology;

/**
 * Load from a set of php files (returning an array) or arrays.
 * Each array MUST be indexed by a generator identifier
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class ArrayPhpFilePasswordTopologyLoader implements PasswordTopologyLoaderInterface
{
    
    /**
     * All topologies
     * 
     * @var PasswordTopologyCollection[]
     */
    private $definitions = [];
    
    /**
     * Initialize loader.
     * Each array MUST follow this convention : 
     * <pre>
     * [
     *      "generator_identifier"  =>  [
     *          "?null"                 =>  ["all topologies declared here will be prioritize on loading no matter what is next"]
     *          "?topology"             =>  "how_many_time_used"
     *      ]
     * ]
     * </pre>
     * 
     * @param array $definitions
     *   All topologies. Can be the array or a php file returning an array
     */
    public function __construct(array $definitions)
    {
        foreach ($definitions as $definition) {
            if(!\is_array($definition))
                $definition = self::include($definition);
            
            $this->definitions[] = $definition;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(?int $limit, string $generator): PasswordTopologyCollection
    {
        $collection = new PasswordTopologyCollection();
        foreach ($this->definitions as $definitions) {
            foreach ($definitions as $topologyGenerator => $topologies) {
                if($topologyGenerator !== $generator)
                    continue;
                foreach ($topologies as $topology => $used) {
                    if(empty($topology)) {
                        foreach ($used as $topology)
                            $collection->add(new PasswordTopology($topology, $generator), null);
                    }
                    else {
                        $collection->add(new PasswordTopology($topology, $generator), $used);
                    }
                }
            }
        }

        return (null === $limit) ? $collection : $collection->extract($limit);
    }
    
    /**
     * Remove access to $this from included file
     *
     * @param string $path
     *  File path
     *
     * @return array
     *   This file MUST return an array
     *
     * @throws \LogicException
     *   If given path does not correspond to a file
     */
    private static function include(string $path): array
    {
        if(!\is_file($path))
            throw new \LogicException("This file '{$path}' does not exist");
            
        return include $path;
    }

}
