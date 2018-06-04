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

namespace NessTest\Component\Password\Generator;

use Ness\Component\Password\Generator\PasswordGeneratorInterface;
use Ness\Component\Password\RegexRange;
use Ness\Component\Password\Generator\RegexRangePasswordGenerator;

/**
 * RegexRangePasswordGenerator testcase
 * 
 * @see \Ness\Component\Password\Generator\RegexRangePasswordGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangePasswordGeneratorTest extends PasswordGeneratorTestCase
{
    
    /**
     * {@inheritDoc}
     * @see \NessTest\Component\Password\Generator\PasswordGeneratorTestCase::getGenerator()
     */
    protected function getGenerator(): PasswordGeneratorInterface
    {
        $ranges = [];
        $range = $this->getMockBuilder(RegexRange::class)->disableOriginalConstructor()->getMock();
        $ranges = array_merge($ranges, range("a", "z"), range("A", "Z"), range(0, 9));
        $range->expects($this->exactly(self::ITERATIONS + 1))->method("getList")->will($this->returnValue($ranges));
        
        $generator = new RegexRangePasswordGenerator();
        $generator->setRange($range);
        
        return $generator;
    }
    
}
