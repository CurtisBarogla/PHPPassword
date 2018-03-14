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

namespace ZoeTest\Component\Password\Common;


use PHPUnit\Framework\TestCase;
use Zoe\Component\Password\Topology\PasswordTopology;

/**
 * Shortcuts for generating helper when testing topologies
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class TopologyShortcut
{
    
    /**
     * Generate a set of password topologies
     * 
     * @param TestCase $case
     *   Case reference
     * @param array[][] $map
     *   Map representing password topologies to load.
     *   [":generatorName:" => [:topologyValues:]]
     * 
     * @return array
     *   A set of mocked password topologies
     */
    public static function generateTopologies(TestCase $case, array $map): array
    {
        $topologies = [];
        foreach ($map as $generatorName => $passwordTopologies) {
            foreach ($passwordTopologies as $topology) {
                $mock = $case->getMockBuilder(PasswordTopology::class)->disableOriginalConstructor()->getMock();
                $mock->expects($case->any())->method("generatedBy")->will($case->returnValue($generatorName));
                $mock->expects($case->any())->method("getTopology")->will($case->returnValue($topology));
                $topologies[$generatorName][] = $mock;
            }
        }
        
        return $topologies;
    }
    
}
