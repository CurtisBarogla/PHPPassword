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
 * Try to load a set of topology from multiple PasswordTopologyLoader implementations
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyLoaderCollection implements PasswordTopologyLoaderInterface
{
    
    /**
     * Configuration of limit imposed to all registered loaders
     * 
     * @var array
     */
    private $configuration;
    
    /**
     * Registered password topology loaders indexed by an identifier
     * 
     * @var PasswordTopologyLoaderInterface[]
     */
    private $loaders;
    
    /**
     * Inititialize loader collection
     * 
     * @param array[] $configuration
     *   Configuration applied to all registered loaders.
     *   Simply an array indexed by an unique identifier furtherly registered into the collection with value the limit of topologies to load for each loaders <br />
     *   Set to -1 this value to get all topologies from the loader
     * @param string $defaultLoaderIdentifier
     *   Default password topology loader identifier
     * @param PasswordTopologyLoaderInterface $defaultLoader
     *   Default password topology loader
     */
    public function __construct(array $configuration, string $defaultLoaderIdentifier, PasswordTopologyLoaderInterface $defaultLoader)
    {
        $this->configuration = $configuration;
        $this->loaders[$defaultLoaderIdentifier] = $defaultLoader;
    }
    
    /**
     * Register a loader into the collection
     * 
     * @param string $identifier
     *   Password topology loader identifier 
     * @param PasswordTopologyLoaderInterface $loader
     *   Password topology loader
     */
    public function addLoader(string $identifier, PasswordTopologyLoaderInterface $loader): void
    {
        $this->loaders[$identifier] = $loader;
    }
    
    /**
     * In this implementation limit parameter will be a fallback if loader has been not found into configuration map else will use a value defined and will be ignored
     * 
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(PasswordTopology $topology, ?int $limit): ?PasswordTopologyCollection
    {
        $finalCollection = new PasswordTopologyCollection();
        
        foreach ($this->loaders as $identifier => $loader) {
            $toLoad = $this->configuration[$identifier] ?? $limit;
            if(null === $loaded = $loader->load($topology, ($toLoad === -1) ? null : $toLoad)) {
                continue;
            }
            if($toLoad === -1) {
                if(!isset($fullCollection))
                    $fullCollection = new PasswordTopologyCollection();
                $fullCollection->merge($loaded);
            } else {
                $finalCollection->merge($loaded);
            }
        }
        
        if(isset($fullCollection)) {
            $finalCollection->merge($fullCollection);
            unset($fullCollection);
        }
            
        return \count($finalCollection) > 0 ? $finalCollection : null;
    }

}
