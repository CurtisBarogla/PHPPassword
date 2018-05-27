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

namespace Ness\Component\Password\Topology\Generator;

use Ness\Component\Password\Password;
use Ness\Component\Password\RegexRangeAwareInterface;
use Ness\Component\Password\Topology\PasswordTopology;
use Ness\Component\Password\Traits\RegexRangeAwareTrait;
use Ness\Component\Password\Exception\UnsupportedPasswordException;

/**
 * Generate a topology from a setted RegexRange.
 * Will use identifiers setted into RegexRange for generating the topology. <br />
 * (e.g) "range1 => [], range2 => []" = "range1range2range1..."
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangePasswordTopologyGenerator implements PasswordTopologyGeneratorInterface, RegexRangeAwareInterface
{
    
    use RegexRangeAwareTrait;
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::generate()
     */
    public function generate(Password $password): PasswordTopology
    {
        if(null === $this->getRange()->preg($password->get()))
            throw new UnsupportedPasswordException("Topology cannot be generated over given password with '{$this->getIdentifier()}' PasswordTopologyGenerator");
        
        $topology = "";
        foreach ($password->getExploded() as $character) {
            $topology .= $this->getRange()->pregRange($character);
        }
        
        return new PasswordTopology($topology, $this->getIdentifier());
    }

    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::getIdentifier()
     */
    public function getIdentifier(): string
    {
        return $this->getRange()->getIdentifier();
    }
    
}
