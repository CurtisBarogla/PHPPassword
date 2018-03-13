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
use Zoe\Component\Password\Exception\UnexpectedMethodCallException;
use Zoe\Component\Password\Topology\Topology;

/**
 * Native password topology generator.
 * Use multiple characters ranges declaration to format a topology or use a default one
 * 
 * @see http://www.utf8-chartable.de/unicode-utf8-table.pl?number=1024
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class NativePasswordTopologyGenerator implements PasswordTopologyGeneratorInterface
{
    
    /**
     * General regex for support
     *
     * @var string
     */
    private $regex;
    
    /**
     * Ranges combined
     *
     * @var string[]
     */
    private $combinations;
    
    /**
     * If supported method has been already called
     *
     * @var bool
     */
    private $supported = false;
    
    /** 
     * Defaults character ranges defined by OWASP
     * 
     * @var array
     */
    private const DEFAULTS_RANGES = [
        "u"     =>  ["A-Z"],
        "l"     =>  ["a-z"],
        "d"     =>  ["0-9"],
        "s"     =>  [
            "\\x{0020}-\\x{002F}", // [ -/]
            "\\x{003A}-\\x{0040}", // [:-@]
            "\\x{005B}-\\x{0060}", // [[-`]
            "\\x{007B}-\\x{007E}"  // [{-~]
        ]
    ];
    
    /**
     * Initialize topology ranges
     *
     * @param array[][]|null $ranges
     *   Accepted ranges. Indexed by replacement char for topology generation.
     *   If setted to null, will set OWASP rules password guidelines
     *   
     * @see https://www.owasp.org/index.php/Authentication_Cheat_Sheet#Password_Complexity
     */
    public function __construct(?array $ranges = null)
    {
        $this->initializeCharacterRanges($ranges ?? self::DEFAULTS_RANGES);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::format()
     */
    public function format(Password $password): Topology
    {
        if(!$this->supported)
            throw new UnexpectedMethodCallException(\sprintf("Password topology cannot be generated if support method has not been called yet over '%s' topology generator",
                $this->getIdentifier()));
            
        $topology = "";
        $password = $password->getExplodedPassword();
        foreach ($password as $character) {
            foreach ($this->combinations as $replacement => $regex) {
                if($this->preg($character, "{1}", $regex)) {
                    $topology .= $replacement;
                    break;
                }
            }            
        }
            
        return new Topology($topology, $this->getIdentifier());
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::support()
     */
    public function support(Password $password): bool
    {
        $this->supported = true;
        
        return $this->preg($password->getValue(), "+", $this->regex);
    }
    
    /**
     * {@inheritDoc}
     * @see \Zoe\Component\Password\Topology\Generator\PasswordTopologyGeneratorInterface::getIdentifier()
     */
    public function getIdentifier(): string
    {
        return "NativePasswordTopologyGenerator";
    }
    
    /**
     * Initialize all characters ranges into class properties
     *
     * @param array[] $ranges
     *   Array indexed by topology character and value the ranges to apply
     */
    protected function initializeCharacterRanges(array $ranges): void
    {
        $regex = "";
        foreach ($ranges as $replacement => $range) {
            $regex = \implode("", $range);
            if(!isset($this->combinations[$replacement]))
                $this->combinations[$replacement] = $regex;
            else
                $this->combinations[$replacement] .= $regex;
        }
        $this->regex = \implode("", $this->combinations);
    }
    
    /**
     * Perform an UTF-8 preg_match over a value combining multiple regex ranges
     *
     * @param string $value
     *   Value to verify
     * @param string $limit
     *   Limit of char authorized (+ by default)
     * @param string $regex
     *   Regex to verify
     *
     * @return bool
     *   True if preg_match return true :)
     *
     * @throws \LogicException
     *   When regex cannot be handled by preg_match
     */
    protected function preg(string $value, string $limit = "+", string $regex): bool
    {
        $regex = "#(*UTF8)^[{$regex}]{$limit}$#";

        if(false !== $result = \preg_match($regex, $value)) {
            return (bool) $result;
        }
        
        throw new \LogicException(\sprintf("This regex '%s' cannot be compiled by preg_match",
            $regex));
    }

}
