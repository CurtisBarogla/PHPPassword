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

namespace Ness\Component\Password\Generator;

use Ness\Component\Password\Password;
use Ness\Component\Password\RegexRangeAwareInterface;
use Ness\Component\Password\Traits\RegexRangeAwareTrait;

/**
 * Generate password from a setted RegexRange
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangePasswordGenerator implements PasswordGeneratorInterface, RegexRangeAwareInterface
{
    
    use RegexRangeAwareTrait;
    
    /**
     * {@inheritDoc}
     * @see \Ness\Component\Password\Generator\PasswordGeneratorInterface::generate()
     */
    public function generate(int $length): Password
    {
        $characters = $this->getRange()->getList();
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[\random_int(0, \count($characters) - 1)]; 
        }
        
        return new Password($password);
    }

}
