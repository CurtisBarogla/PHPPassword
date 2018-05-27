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

/**
 * Load topologies from external sources
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface PasswordTopologyLoaderInterface
{
    
    /**
     * Load a topology collection
     * 
     * @param int|null $limit
     *   Limit of topologies to load. If setted to null, MUST load all topologies
     * @param string $generator
     *   Generator which the topologies are loaded
     * 
     * @return PasswordTopologyCollection
     *   All topologies loadables. If no topology are found, MUST return an empty collection
     */
    public function load(?int $limit, string $generator): PasswordTopologyCollection;
    
}
