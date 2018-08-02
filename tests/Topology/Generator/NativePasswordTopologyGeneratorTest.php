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

namespace NessTest\Component\Password\Topology\Generator;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Topology\Generator\NativePasswordTopologyGenerator;
use Ness\Component\Password\Password;

/**
 * NativePasswordTopologyGenerator testcase
 * 
 * @see \Ness\Component\Password\Topology\Generator\NativePasswordTopologyGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyGeneratorTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::generate()
     */
    public function testGenerate(): void
    {
        $generator = new NativePasswordTopologyGenerator();
        
        $samples = ["ddllssdd" => "42ke*/47", "ssuullssdd" => "@*JZmoé'78", "ulsdulsd" => "Ym*9Pa@7"];
        
        foreach ($samples as $topology => $passwordValue) {
            $password = $this->getMockBuilder(Password::class)->disableOriginalConstructor()->getMock();
            $password->expects($this->once())->method("getExploded")->will($this->returnValue(\preg_split("//u", $passwordValue, 0, PREG_SPLIT_NO_EMPTY)));
            $generated = $generator->generate($password);
            $this->assertSame($generated->get(), $topology);
        }
    }
    
    /**
     * @see \Ness\Component\Password\Topology\Generator\NativePasswordTopologyGenerator::getIdentifier()
     */
    public function testGetIdentifier(): void
    {
        $generator = new NativePasswordTopologyGenerator();
        
        $this->assertSame("NativePasswordTopologyGenerator", $generator->getIdentifier());
    }
    
}