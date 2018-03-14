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
     * @var PasswordTopology[]|null
     */
    private $restrictedTopologies;
    
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
        $this->initializeRestrictedPasswordTopologies($passwordTopology->generatedBy());
        
        foreach ($this->restrictedTopologies as $topology) {
            if($passwordTopology->generatedBy() !== $topology->generatedBy())
                continue;
                
            if($passwordTopology->getTopology() === $topology->getTopology())
                return false;
        }
        
        return true;
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
    public function getRestrictedPasswordTopologies(string $generatorIdentifier): array
    {
        $this->initializeRestrictedPasswordTopologies($generatorIdentifier);
        
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
     */
    private function initializeRestrictedPasswordTopologies(string $generatorIdentifier): void
    {
        if(null === $this->limit)
            throw new \LogicException("Cannot initialize a set of restricted password topologies as no limit has been defined");
        
        if(null !== $this->restrictedTopologies)
            return;
        
        $this->restrictedTopologies = $this->loader->load($generatorIdentifier, $this->limit);
    }

}
