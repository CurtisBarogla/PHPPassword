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

namespace Zoe\Component\Password\Topology\Generator;

/**
 * Native password topology generator.
 * Support : [ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz !"#$%&'()*+,-./:;<=>?@[\]^_`{|}~]
 * 
 * @see http://www.utf8-chartable.de/unicode-utf8-table.pl?number=1024
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyGenerator extends AbstractPasswordTopologyGenerator
{
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::getIdentifier()
     */
    public function getIdentifier(): string
    {
        return "NativePasswordTopologyGenerator";
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::getUppercaseCharacterRanges()
     */
    protected function getUppercaseCharacterRanges(): array
    {
        return [
            "A-Z",
        ];
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::getLowercaseCharacterRanges()
     */
    protected function getLowercaseCharacterRanges(): array
    {
        return [
            "a-z",
        ];
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::getDigitCharacterRanges()
     */
    protected function getDigitCharacterRanges(): array
    {
        return [
            "0-9"
        ];
    }

    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\AbstractPasswordTopologyGenerator::getSpecialCharacterRanges()
     * 
     * @see https://www.owasp.org/index.php/Password_special_characters
     */
    protected function getSpecialCharacterRanges(): array
    {
        // Recommended specials characters - https://www.owasp.org/index.php/Password_special_characters
        return [
            "\\x{0020}-\\x{002F}", // [ -/]
            "\\x{003A}-\\x{0040}", // [:-@]
            "\\x{005B}-\\x{0060}", // [[-`]
            "\\x{007B}-\\x{007E}"  // [{-~]
        ];
    }

}