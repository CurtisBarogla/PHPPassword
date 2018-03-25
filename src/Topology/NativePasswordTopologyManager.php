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

use Zoe\Component\Password\Password;
use Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface;
use Zoe\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;
use Zoe\Component\Password\Exception\UnexceptedPasswordFormatException;

/**
 * Native basic implementation of a password topology manager
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyManager implements PasswordTopologyManagerInterface
{
    
    /**
     * Password topology generator
     * 
     * @var PasswordTopologyGeneratorInterface
     */
    private $generator;
    
    /**
     * Password topology loader
     * 
     * @var PasswordTopologyLoaderInterface
     */
    private $loader;
    
    /**
     * Limit of topologie to load
     * 
     * @var int|null
     */
    private $limit;
    
    /**
     * Restricted password topologies
     * 
     * @var PasswordTopologyCollection|null
     */
    private $restrictedTopologies;
    
    /**
     * If restricted password topologies has been loaded
     * 
     * @var string
     */
    private $initialized = false;
    
    /**
     * Initialize password topology manager
     * 
     * @param PasswordTopologyGeneratorInterface $generator
     *   Password topology generator
     * @param PasswordTopologyLoaderInterface $loader
     *   Password topology loader
     * @param int $limit
     *   Limit of topologies to load over the loader. All topologies loaded will reject Topology given to isSecure method
     */
    public function __construct(
        PasswordTopologyGeneratorInterface $generator, 
        PasswordTopologyLoaderInterface $loader,
        ?int $limit = null)
    {
        $this->generator = $generator;
        $this->loader = $loader;
        
        if(null !== $limit)
            $this->setLimit($limit);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\PasswordTopologyManagerInterface::isSecure()
     */
    public function isSecure(PasswordTopology $passwordTopology): bool
    {
        $this->initializeRestrictedPasswordTopologies($passwordTopology);
        
        if(null === $this->restrictedTopologies)
            return true;
        
        if($this->restrictedTopologies->getCollectionGeneratorIdentifier() !== $passwordTopology->generatedBy())
            return true;
            
        return !isset($this->restrictedTopologies[$passwordTopology->getTopology()]);
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\PasswordTopologyManagerInterface::generate()
     */
    public function generate(Password $password): PasswordTopology
    {
        if(!$this->generator->support($password))
            throw new UnexceptedPasswordFormatException(\sprintf("Cannot generate a topology over given password with setted topology generator '%s'",
                $this->generator->getIdentifier()));
            
        return $this->generator->format($password);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\PasswordTopologyManagerInterface::getRestrictedPasswordTopologies()
     */
    public function getRestrictedPasswordTopologies(): ?PasswordTopologyCollection
    {
        $this->initializeRestrictedPasswordTopologies();
        
        return $this->restrictedTopologies;
    }
    
    /**
     * Override limit parameter
     * 
     * @param int $limit
     *   Limit of password topologies restricted
     */
    public function setLimit(int $limit): void
    {
        $this->limit = $limit;
    }
    
    /**
     * Initialize locale property for restricted password topologies
     * 
     * @param string $generatorIdentifier
     *   Password topology generator identifier
     *   
     * @throws \LogicException
     *   When no limit has been defined
     * @throws \LogicException
     *   When no topology has been given as property has been not already initialized
     */
    private function initializeRestrictedPasswordTopologies(?PasswordTopology $topology = null): void
    {
        if(null === $this->limit)
            throw new \LogicException("Cannot initialize a set of restricted password topologies as no limit has been defined");
        
        if($this->initialized)
            return;
        
        if(!$this->initialized && null === $topology)
            throw new \LogicException("To get restricted password topologies, you need to initialize the manager by giving a PasswordTopology. Use isSecure method");
        
        $this->restrictedTopologies = $this->loader->load($topology, $this->limit);
        $this->initialized = true;
    }

}
