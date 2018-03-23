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

namespace ZoeTest\Component\Password\Topology\Loader;

use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader;

/**
 * NativePasswordTopologyLoader testcase
 * 
 * @see \Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyLoaderTest extends TestCase
{
    
    /**
     * @see \Zoe\Component\Password\Topology\Loader\NativePasswordTopologyLoader::load()
     */
    public function testLoad(): void
    {
        $topologies = [
            "BarIdentifier"     =>  [
                "foo",
                "bar",
                "moz"
            ]
        ];
        
        $loader = new NativePasswordTopologyLoader($topologies);
        $this->assertSame([], $loader->load("FooIdentifier", 42));
        $topologies = $loader->load("BarIdentifier", 2);
        
        $this->assertCount(2, $topologies);
        $this->assertSame("foo", $topologies[0]->getTopology());
        $this->assertSame("BarIdentifier", $topologies[0]->generatedBy());
        $this->assertSame("bar", $topologies[1]->getTopology());
        $this->assertSame("BarIdentifier", $topologies[1]->generatedBy());
    }
    
}
