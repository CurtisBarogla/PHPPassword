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
use Zoe\Component\Password\Topology\Topology;

/**
 * Topology testcase
 * 
 * @see \Zoe\Component\Password\Topology\Topology
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TopologyTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Topology\Topology::getTopology()
     */
    public function testGetTopology(): void
    {
        $topology = new Topology("Foo", "Bar");
        
        $this->assertSame("Foo", $topology->getTopology());
    }
    
    /**
     * @see \Zoe\Component\Password\Topology\Topology::generatedBy()
     */
    public function testGeneratedBy(): void
    {
        $topology = new Topology("Foo", "Bar");
        
        $this->assertSame("Bar", $topology->generatedBy());
    }
    
}
