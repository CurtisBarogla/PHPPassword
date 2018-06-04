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

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Generator\PasswordGeneratorInterface;

/**
 * Common to all PasswordGenerator implementation test cases
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class PasswordGeneratorTestCase extends PasswordTestCase
{
    
    /**
     * Number of iterations
     * 
     * @var int
     */
    protected const ITERATIONS = 64;
    
    /**
     * Password length generated
     * 
     * @var int
     */
    protected const PASSWORD_LENGTH = 128;
    
    /**
     * @see \Ness\Component\Password\Generator\PasswordGeneratorInterface::generate()
     */
    public function testGenerate(): void
    {
        $generator = $this->getGenerator();
        for ($i = 0; $i < self::ITERATIONS; $i++) {
            if(!isset($base))
                $base = $generator->generate(self::PASSWORD_LENGTH);
            $current = $generator->generate(self::PASSWORD_LENGTH);
            $this->assertCount(self::PASSWORD_LENGTH, $current);
            $this->assertNotSame($current->get(), $base->get());
            $base = $current;
        }
    }
    
    /**
     * Initialize testes password generator
     * 
     * @return PasswordGeneratorInterface
     *   Password generator implementation
     */
    abstract protected function getGenerator(): PasswordGeneratorInterface;
    
}
