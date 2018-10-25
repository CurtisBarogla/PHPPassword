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
 * Try to find topologie from a set of loaders
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class ChainPasswordTopologyLoader implements PasswordTopologyLoaderInterface
{
    
    /**
     * Loaders registered
     * 
     * @var PasswordTopologyLoaderInterface[]
     */
    private $loaders;
    
    /**
     * Initialize loader
     * 
     * @param PasswordTopologyLoaderInterface $defaultLoader
     *   Default topology loader
     */
    public function __construct(PasswordTopologyLoaderInterface $defaultLoader)
    {
        $this->loaders[] = $defaultLoader;
    }
    
    /**
     * Add a loader to the collection
     * 
     * @param PasswordTopologyLoaderInterface $loader
     *   Topology loader
     */
    public function addLoader(PasswordTopologyLoaderInterface $loader): void
    {
        $this->loaders[] = $loader;
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface::load()
     */
    public function load(?int $limit, string $generator): PasswordTopologyCollection
    {
        $collection = new PasswordTopologyCollection();
        foreach ($this->loaders as $loader)         
            $collection->merge($loader->load($limit, $generator));
        
        return $collection;
    }

}
