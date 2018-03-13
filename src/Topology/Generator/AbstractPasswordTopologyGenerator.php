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

use Zoe\Component\Password\Password;
use Zoe\Component\Password\Topology\Topology;
use Zoe\Component\Password\Exception\UnexpectedPasswordFormatException;

/**
 * Basic implementation taking multiple ranges of chars to generate a password topology.
 * Use format : [u = uppercase, l = lowercase, s = special, d = digit] to format the topology
 * 
 * @see https://www.owasp.org/index.php/Password_special_characters
 * @see https://www.owasp.org/index.php/Authentication_Cheat_Sheet#Password_Topologies
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
abstract class AbstractPasswordTopologyGenerator implements PasswordTopologyGeneratorInterface
{
    
    /**
     * Combined characters ranges for uppercase
     * 
     * @var string|null
     */
    private $combineUppercase = null;
    
    /**
     * Combined characters ranges for lowercase
     *
     * @var string|null
     */
    private $combineLowercase = null;
    
    /**
     * Combined characters ranges for digit
     *
     * @var string|null
     */
    private $combineDigit = null;
    
    /**
     * Combined characters ranges for special
     *
     * @var string|null
     */
    private $combineSpecial = null;
    
    /**
     * Initialize regex ranges properties
     */
    public function __construct()
    {
        $this->initializeCharacterRanges(
            [
                "combineUppercase"  =>  $this->getUppercaseCharacterRanges(),
                "combineLowercase"  =>  $this->getLowercaseCharacterRanges(),
                "combineDigit"      =>  $this->getDigitCharacterRanges(),
                "combineSpecial"    =>  $this->getSpecialCharacterRanges()
            ]
        );
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::format()
     */
    public function format(Password $password): Topology
    {
        $preg = function(string $character, string $regex): bool {
            return $this->preg($character, "{1}", $regex);   
        };
        $topology = "";
        foreach ($password->getExplodedPassword() as $character) {
            if($preg($character, $this->combineUppercase)) {
                $topology .= "u";
            } elseif ($preg($character, $this->combineLowercase)) {
                $topology .= "l";
            } elseif ($preg($character, $this->combineDigit)) {
                $topology .= "d";
            } elseif ($preg($character, $this->combineSpecial)) {
                $topology .= "s";
            } else {
                // should never happen as support method should be called before, but in case
                throw new UnexpectedPasswordFormatException(\sprintf("This character '%s' is not handled by password topology generator '%s'",
                    $character,
                    $this->getIdentifier()));
            }
        }
        
        return new Topology($topology, $this->getIdentifier());
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::support()
     */
    public function support(Password $password): bool
    {;
        return $this->preg($password->getValue(), "+", 
            $this->combineUppercase, 
            $this->combineLowercase, 
            $this->combineDigit, 
            $this->combineSpecial);
    }
    
    /**
     * Initialize all characters ranges into class properties 
     * 
     * @param array[][] $ranges
     *   Array indexed by property to initialize and as value an array of characters ranges
     */
    protected function initializeCharacterRanges(array $ranges): void
    {
        $regex = "";
        foreach ($ranges as $property => $range) {
            if(null === $this->{$property}) {
                $this->{$property} = \implode("", $range);
            }
        }
    }
    
    /**
     * Perform an UTF-8 preg_match over a value combining multiple regex ranges
     * 
     * @param string $value
     *   Value to verify
     * @param string $limit
     *   Limit of char authorized (+ by default)
     * @param string ...$regexRanges
     *   Ranges to combine and verify
     * 
     * @return bool
     *   True if preg_match return true :)
     *   
     * @throws \LogicException
     *   When regex cannot be handled by preg_match
     */
    protected function preg(string $value, string $limit = "+", string... $regexRanges): bool
    {
        $regex = \implode("", $regexRanges);
        $regex = "#(*UTF8)^[{$regex}]{$limit}$#";
        
        if(false !== $result = \preg_match($regex, $value)) {
            return (bool) $result;
        }
        
        throw new \LogicException(\sprintf("This regex '%s' cannot be compiled by preg_match",
            $regex));
    }

    /**
     * Define ranges of characters that describe a character uppercased
     * Will be used to create a final regex (combined with other get*CharactersRanges) to determine if the generator handle a password
     *
     * @return array
     *   Ranges of characters describing a character uppercased
     */
    abstract protected function getUppercaseCharacterRanges(): array;
    
    /**
     * Define ranges of characters that describe a character lowercased
     * Will be used to create a final regex (combined with other get*CharactersRanges) to determine if the generator handle a password
     *
     * @return array
     *   Ranges of characters describing a character lowercased
     */
    abstract protected function getLowercaseCharacterRanges(): array;
    
    /**
     * Define ranges of characters that describe a digit character
     * Will be used to create a final regex (combined with other get*CharactersRanges) to determine if the generator handle a password
     *
     * @return array
     *   Ranges of characters describing a digit character
     */
    abstract protected function getDigitCharacterRanges(): array;
    
    /**
     * Define ranges of characters that describe a character considered special
     * Will be used to create a final regex (combined with other get*CharactersRanges) to determine if the generator handle a password
     *
     * @return array
     *   Ranges of characters describing a character considered special
     */
    abstract protected function getSpecialCharacterRanges(): array;
    
}
