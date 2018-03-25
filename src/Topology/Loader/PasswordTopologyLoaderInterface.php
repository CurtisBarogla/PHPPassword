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

use Zoe\Component\Password\Topology\PasswordTopologyCollection;
use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * Responsible to load a set of password topologies 
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyLoaderInterface
{
    
    /**
     * Load a set of most used password topologies from externals sources from a given topology.
     * Basically, topologies loaded here will be used to refute password which topology has been generated 
     * 
     * @param PasswordTopology $topology
     *   Password topology which topologies must be loaded
     * @param int|null $limit
     *   Number of topologies to load. If setted to null, all topologies loadable MUST be returned
     * 
     * @return PasswordTopologyCollection|null
     *   A collection of PasswordTopology or null if no topology has been found
     */
    public function load(PasswordTopology $topology, ?int $limit): ?PasswordTopologyCollection;
    
}
