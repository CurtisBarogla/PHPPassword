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
use Ness\Component\Password\Topology\PasswordTopologyCollection;

/**
 * Common to all topologies testcase
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class PasswordTopologyTestCase extends PasswordTestCase
{
    
    /**
     * Inject value into generatedBy and topologies properties
     *
     * @param PasswordTopologyCollection $collection
     *   Collection which to inject the values
     * @param string|null $generator
     *   Generator setted
     * @param array|null $inject
     *   Topologies to inject
     */
    protected function injectForTest(
        PasswordTopologyCollection $collection,
        ?string $generator = null,
        ?array $inject = null,
        ?int $restricted = null): void
        {
            $reflection = new \ReflectionClass($collection);
            
            if(null !== $inject) {
                $topologiesProperty = $reflection->getProperty("topologies");
                $topologiesProperty->setAccessible(true);
                $topologiesProperty->setValue($collection, $inject);
            }
            if(null !== $generator) {
                $generatorProperty = $reflection->getProperty("generatedBy");
                $generatorProperty->setAccessible(true);
                $generatorProperty->setValue($collection, $generator);
            }
            if(null !== $generator) {
                $restrictedProperty = $reflection->getProperty("restricted");
                $restrictedProperty->setAccessible(true);
                $restrictedProperty->setValue($collection, $restricted);
            }
    }
    
    /**
     * Extract value of the topologies properties of a collection
     *
     * @param PasswordTopologyCollection $collection
     *   Collection the the value of the property must be extract
     *
     * @return array
     *   Value of the property
     */
    protected function extractTopologies(PasswordTopologyCollection $collection): array
    {
        $reflection = new \ReflectionClass($collection);
        $topologiesProperty = $reflection->getProperty("topologies");
        $topologiesProperty->setAccessible(true);
        
        return $topologiesProperty->getValue($collection);
    }
    
}
