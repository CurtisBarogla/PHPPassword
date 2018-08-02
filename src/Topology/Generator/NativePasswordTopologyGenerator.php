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
use Ness\Component\Password\Topology\PasswordTopology;

/**
 * Simple generator based on simple rules.
 * Based on https://www.owasp.org/index.php/Authentication_Cheat_Sheet#Password_Topologies
 * 
 * @see https://www.owasp.org/index.php/Authentication_Cheat_Sheet#Password_Topologies
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyGenerator implements PasswordTopologyGeneratorInterface
{
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::generate()
     */
    public function generate(Password $password): PasswordTopology
    {
        $topology = "";
        
        foreach ($password->getExploded() as $char) {
            if(\in_array($char, \range('A', 'Z')))
                $topology .= 'u';
            elseif(\in_array($char, \range('a', 'z')))
                $topology .= 'l';
            elseif(\is_numeric($char))
                $topology .= 'd';        
            else
                $topology .= 's';
        }
        
        return new PasswordTopology($topology, $this->getIdentifier());
    }
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::getIdentifier()
     */
    public function getIdentifier(): string
    {
        return "NativePasswordTopologyGenerator";
    }
    
}
