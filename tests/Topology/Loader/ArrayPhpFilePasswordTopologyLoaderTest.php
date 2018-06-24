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

namespace NessTest\Component\Password\Topology\Loader;

use Ness\Component\Password\Topology\Loader\ArrayPhpFilePasswordTopologyLoader;
use NessTest\Component\Password\Topology\PasswordTopologyTestCase;

/**
 * ArrayPhpFilePasswordTopologyLoader testcase
 * 
 * @see \Ness\Component\Password\Topology\Loader\ArrayPhpFilePasswordTopologyLoader
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class ArrayPhpFilePasswordTopologyLoaderTest extends PasswordTopologyTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\ArrayPhpFilePasswordTopologyLoader::load()
     */
    public function testLoad(): void
    {
        $topologies = [
            "FooGenerator"  =>  [
                null            =>  ["noz", "loz"],
                "poz"           =>  2
            ]
        ];
        $loader = new ArrayPhpFilePasswordTopologyLoader([__DIR__."/../../Fixtures/Topology/Loader/arrayTopologyFile.php", $topologies]);
        

        $topologies = $this->extractTopologies($loader->load(2, "FooGenerator"));
        $this->assertSame(["foo" => null, "bar" => null, "moz" => null, "noz" => null, "loz" => null, "moze" => 7, "poz" => 5], $topologies);
        $topologies = $this->extractTopologies($loader->load(null, "FooGenerator"));
        $this->assertSame(["loz" => null, "noz" => null, "moz" => null, "bar" => null, "foo" => null,"poz" => 5, "poze" => 1, "moze" => 7], $topologies);
        $topologies = $this->extractTopologies($loader->load(1, "BarGenerator"));
        $this->assertSame(["foo" => null, "bar" => null, "moz" => null, "poz" => 3], $topologies);
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\ArrayPhpFilePasswordTopologyLoader::__construct()
     */
    public function testExceptionWhenAFileIsNotFound(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("This file 'Foo' does not exist");
        
        $loader = new ArrayPhpFilePasswordTopologyLoader(["Foo"]);
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Loader\ArrayPhpFilePasswordTopologyLoader::__construct()
     */
    public function testExceptionWhenAFileDoesNotReturnAnArray(): void
    {
        $file = __DIR__."/../../Fixtures/Topology/Loader/invalid_file.php";
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("This file '{$file}' MUST return an array");
        
        $loader = new ArrayPhpFilePasswordTopologyLoader([$file]);
    }
    
}
