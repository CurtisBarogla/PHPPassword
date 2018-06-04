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
use Ness\Component\Password\Generator\NativePasswordGenerator;

/***
 * NativePasswordGenerator testcase
 * 
 * @see \Ness\Component\Password\Generator\NativePasswordGenerator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordGeneratorTest extends PasswordGeneratorTestCase
{
    
    /**
     * {@inheritDoc}
     * @see \NessTest\Component\Password\Generator\PasswordGeneratorTestCase::getGenerator()
     */
    protected function getGenerator(): PasswordGeneratorInterface
    {
        return new NativePasswordGenerator();
    }
    
}
