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

namespace Ness\Component\Password;

use function Ness\Component\Password\mb_range;

/**
 * Simple wrapper around regexes to compile multiple ranges of characters regrouped over an unique identifier.
 * Not sure if it is really useful ^^, but for now...
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRange implements \Countable
{
    
    /**
     * Map representing the characters list from ranges
     * 
     * @var array[array]
     */
    private $map = [];
    
    /**
     * All ranges characters declared into all ranges
     * 
     * @var string
     */
    private $global = "";
    
    /**
     * Hash table referencing what a character is given the ranges
     * 
     * @var array[string]
     */
    private $hash = [];
    
    /**
     * Already parsed/verified values
     * 
     * @var array[int|null]
     */
    private $table = [];
    
    /**
     * Range identifier
     * 
     * @var string
     */
    private $identifier;

    /**
     * Identify the regex range.
     * 
     * @return string
     *   Regex range identifier
     *   
     * @throws \LogicException
     *   When no range has been setted
     */
    public function getIdentifier(): string
    {
        if(null !== $this->identifier)
            return $this->identifier;
        
        if(empty($this->map))
            throw new \LogicException("Impossible to get the identifier of an empty RegexRange");

        return $this->identifier = sha1(\implode("&", \array_map(function(array $range, string $identifier): string {
            $max = $range["max"] ?? "null";
            return "{$identifier}@{$range["list"]}:{$range["min"]}-{$max}";       
        }, $this->map, \array_keys($this->map))));
    }
    
    /**
     * Get all ranges declared indexed by the identifier
     *  
     * @return array
     *   All ranges
     */
    public function getRanges(): array
    {
        return $this->map;
    }
    
    /**
     * Return a list of all characters ranges defined
     * 
     * @return array
     *   List of all characters of defined ranges
     */
    public function getList(): array
    {
        return \array_keys($this->hash);
    }
    
    /**
     * Add a set of characters ranges
     * 
     * @param string $identifier
     *   Ranges identifier
     * @param array $ranges
     *   Characters ranges (e.g : "A-Z" for all uppercases)
     * @param int|null $min
     *   Minimum of characters required. Set to null (or 1) to at least once 
     * @param int|null $max
     *   Maximum of characters allowed. Set to null if unlimited
     *   
     * @throws \LogicException
     *   When min is greater or equal than max
     */
    public function add(string $identifier, array $ranges, ?int $min = null, ?int $max = null): void
    {
        if(isset($this->map[$identifier]))
            throw new \LogicException("This identifier '{$identifier}' has been already setted into '{$this->getIdentifier()}' regex range");
        
        if(null !== $max && $min >= $max) 
            throw new \LogicException("Min cannot be greater or equal than max on '{$identifier}' range");
        
        \sort($ranges);
            
        foreach ($ranges as $index => $range) {
            if(\mb_strlen($range) !== 3 || \mb_strpos($range, '-', 1) !== 1)
                throw new \LogicException("Range '{$range}' on '{$identifier}' identifier MUST respect pattern : 'char_start'-'char-end'");
            $exploded = ($range[0] === '-') ? ['-', \mb_substr($range, \mb_strpos($range, '-') - 1)] : \explode('-', $range, 2);
            foreach (mb_range($exploded[0], $exploded[1]) as $character) {
                if(isset($this->hash[$character]))
                    throw new \LogicException("This character '{$character}' has been already registered under '{$this->hash[$character]}' identifier");
                $this->hash[$character] = $identifier;
            }
        }

        $this->global .= $this->map[$identifier]["list"] = \preg_quote(\implode("", \array_keys(\array_filter($this->hash, function(string $localIdentifier) use ($identifier): bool {
            return $localIdentifier === $identifier;
        }))), '#');
        $this->map[$identifier]["min"] = $min ?? 1;
        $this->map[$identifier]["max"] = $max;
        
        \ksort($this->map);
    }
    
    /**
     * Preg match a string over compiled ranges and conditions setted
     * 
     * @param string $string
     *   String to match
     * 
     * @return int|null
     *   Number of ranges matched or null if the given string is invalid
     */
    public function preg(string $string): ?int
    {
        // no need to go further if already passed earlier
        if(\array_key_exists($string, $this->table))
            return $this->table[$string];

        $global = null;
        if(!$this->validate("^[{$this->global}]+$", $string, $global))
            return $this->table[$string] = null;
            
        $total = \count($this);
        foreach ($this->map as $identifier => $map) {
            $matches = null;
            if(!$this->validate("[{$map["list"]}]+", $global[0], $matches, true)) {
                $total--;
                continue;
            }

            $result = \mb_strlen(\implode("", $matches[0]));

            if( ($result < $map["min"]) || (null !== $map["max"] && $result > $map["max"]) )
                $total--;
        }

        return $this->table[$string] = $total;
    }
    
    /**
     * Get the range identifier of the given character.
     *
     * @param string $character
     *   Character to get the range
     * 
     * @return string|null
     *   Range identifier or null if the given character does not correspond to a registered one
     */
    public function pregRange(string $character): ?string
    {
        return $this->hash[$character] ?? null;
    }
    
    /**
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count(): int
    {
        return \count($this->map);
    }
    
    /**
     * Validate and compile the regex
     * 
     * @param string $regex
     *   Regex to compile and validate
     * @param string $string
     *   String to match
     * @param array|null $matches
     *   Holds characters matched by the preg_* call
     * @param bool $all
     *   Switch between preg_match or preg_match_all
     * 
     * @return bool
     *   True if the given string match the given regex
     * 
     * @throws \LogicException
     *   If the regex cannot be compiled
     */
    private function validate(string $regex, string $string, ?array& $matches, bool $all = false): bool
    {
        $regex = "#(*UTF8){$regex}#";
        
        $result = ($all) ? \preg_match_all($regex, $string, $matches) : \preg_match($regex, $string, $matches);
        
        if(false === $result)
            throw new \LogicException("This regex '{$regex}' cannot be compiled by preg_match_*");
        
        return (bool) $result;
    }
    
}
