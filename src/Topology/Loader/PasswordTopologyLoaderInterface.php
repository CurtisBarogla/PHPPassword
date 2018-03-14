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

use Zoe\Component\Password\Topology\Topology;

/**
 * Responsible to load a set of password topologies 
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyLoaderInterface
{
    
    /**
     * Load a set of most used password topologies from externals sources.
     * Basically, topologies loaded here will be used to refute password which topology has been generated 
     * 
     * @param int $limit
     *   Number of topologies to load
     * 
     * @return Topology[]
     *   A set of password topologies
     */
    public function load(int $limit): array;
    
}
