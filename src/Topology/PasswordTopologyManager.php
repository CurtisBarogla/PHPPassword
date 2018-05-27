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

namespace Ness\Component\Password\Topology;

use Ness\Component\Password\Password;
use Ness\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface;
use Ness\Component\Password\Topology\Loader\PasswordTopologyLoaderInterface;

/**
 * Native implementation of PasswordTopologyManagerInterface.
 * This implementation use a generator and a loader to handle the requirements
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyManager implements PasswordTopologyManagerInterface
{

    /**
     * Password topology generator
     * 
     * @var PasswordTopologyGeneratorInterface
     */
    private $generator;
    
    /**
     * Topology loader
     * 
     * @var PasswordTopologyLoaderInterface
     */
    private $loader;
    
    /**
     * Determine topologies loadable and therefore considered to commun and unsecure
     * 
     * @var int
     */
    private $limit;
    
    /**
     * Initialize the manager
     * 
     * @param PasswordTopologyGeneratorInterface $generator
     *   Responsible to generate the topologies
     * @param PasswordTopologyLoaderInterface $loader
     *   Deny all topologies loaded
     * @param int $limit
     *   Number of topologies loaded and therefore considered too commun and unsecure
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
     * @see \Ness\Component\Password\Topology\PasswordTopologyManagerInterface::generate()
     */
    public function generate(Password $password): PasswordTopology
    {
        return $this->generator->generate($password);
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\PasswordTopologyManagerInterface::isSecure()
     */
    public function isSecure(PasswordTopology $topology): bool
    {
        return !$this->loader->load($this->limit, $topology->generatedBy())->has($topology);
    }
    
}
