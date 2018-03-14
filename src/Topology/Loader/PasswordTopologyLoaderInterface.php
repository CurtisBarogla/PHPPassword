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
 * Responsible to load a set of password topologies 
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyLoaderInterface
{
    
    /**
     * Load a set of most used password topologies from externals sources by generator identifier.
     * Basically, topologies loaded here will be used to refute password which topology has been generated 
     * 
     * @param string $generatorIdentifier
     *   Generator identifier which topologies was generated loadable
     * @param int $limit
     *   Number of topologies to load
     * 
     * @return PasswordTopology[]
     *   A set of password topologies
     */
    public function load(string $generatorIdentifier, int $limit): array;
    
}
