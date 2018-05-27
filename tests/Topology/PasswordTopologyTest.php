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

namespace NessTest\Component\Password\Topology;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Topology\PasswordTopology;

/**
 * PasswordTopology testcase
 * 
 * @see \Ness\Component\Password\Topology\PasswordTopology
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopology::get()
     */
    public function testGet(): void
    {
        $topology = new PasswordTopology("Foo", "Bar");
        
        $this->assertSame("Foo", $topology->get());
    }
    
    /**
     * @see \Ness\Component\Password\Topology\PasswordTopology::generatedBy()
     */
    public function testGeneratedBy(): void
    {
        $topology = new PasswordTopology("Foo", "Bar");
        
        $this->assertSame("Bar", $topology->generatedBy());
    }
    
}
