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

namespace ZoeTest\Component\Password\Topology;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * Topology testcase
 * 
 * @see \Zoe\Component\Password\Topology\Topology
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopology::getTopology()
     */
    public function testGetTopology(): void
    {
        $topology = new PasswordTopology("Foo", "Bar");
        
        $this->assertSame("Foo", $topology->getTopology());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\PasswordTopology::generatedBy()
     */
    public function testGeneratedBy(): void
    {
        $topology = new PasswordTopology("Foo", "Bar");
        
        $this->assertSame("Bar", $topology->generatedBy());
    }
    
}
