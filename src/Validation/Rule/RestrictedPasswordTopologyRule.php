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

namespace Zoe\Component\Password\Validation\Rule;

use Zoe\Component\Password\Password;
use Zoe\Component\Password\Topology\PasswordTopologyManagerInterface;
use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * Restrict password topology
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RestrictedPasswordTopologyRule extends PasswordRule
{
    
    /**
     * Password topology manager
     * 
     * @var PasswordTopologyManagerInterface
     */
    private $passwordTopologyManager;
    
    /**
     * Limit of topologies to restrict
     * 
     * @var int
     */
    private $limit;
    
    /**
     * Initialize rule
     * 
     * @param string $error
     *   Error message when topology from the given password is restricted.
     *   Restricted topologies can be displayed using {:restricted_topologies:} placeholders and current topology via {:current_topology:}
     * @param PasswordTopologyManagerInterface $passwordTopologyManager
     *   Password topology manager
     * @param int $limit
     *   Limit of topologies to restrict
     */
    public function __construct(string $error, PasswordTopologyManagerInterface $passwordTopologyManager, int $limit)
    {
        parent::__construct($error);
        $this->passwordTopologyManager = $passwordTopologyManager;
        $this->passwordTopologyManager->setLimit($limit);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Validation\Rule\PasswordRuleInterface::comply()
     */
    public function comply(Password $password): bool
    {
        $topology = $this->passwordTopologyManager->generate($password);
        
        if(!$this->passwordTopologyManager->isSecure($topology)) {
            $this->error = $this->interpolate(
                [                
                    "restricted_topologies",
                    "current_topology"
                ], 
                [
                    \implode(
                        ", ", 
                        \array_map(function(PasswordTopology $topology): string {
                            return $topology->getTopology();
                        }, $this->passwordTopologyManager->getRestrictedPasswordTopologies($topology->generatedBy()))
                    ),
                    $topology->getTopology()
                ], 
                $this->error);
            
            return false;
        }
        
        return true;
    }

}
