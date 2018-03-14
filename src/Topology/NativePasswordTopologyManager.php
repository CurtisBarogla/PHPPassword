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
     * @var int
     */
    private $limit;
    
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
        int $limit)
    {
        $this->generator = $generator;
        $this->loader = $loader;
        $this->limit = $limit;
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\PasswordTopologyManagerInterface::isSecure()
     */
    public function isSecure(PasswordTopology $passwordTopology): bool
    {
        $topologies = $this->loader->load($this->limit);
        
        foreach ($topologies as $topology) {
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

}
