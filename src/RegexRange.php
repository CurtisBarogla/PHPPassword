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
     * Map representing the regexes
     * 
     * @var array[array]
     */
    private $map = [];
    
    /**
     * Formatted regex
     * 
     * @var string
     */
    private $global = "";
    
    /**
     * Add to formated regex for preg
     * 
     * @var string
     */
    private $postBuild = "";
    
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
     * Builded over registered ranges
     * 
     * @var string
     */
    private $identifier = "";
    
    /**
     * Initialize the regex range
     * 
     * @param string $identifier
     *   Identify the range
     */
    public function __construct(string $identifier)
    {
        $this->identifier = $identifier;
    }
    
    /**
     * Identify the regex range.
     * 
     * @return string
     *   Regex range identifier
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
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
     * Add a set of characters ranges
     * 
     * @param string $identifier
     *   Ranges identifier
     * @param array $ranges
     *   Characters ranges
     * @param int|null $min
     *   Minimum of characters required. Set to null (or 1) to at least once 
     * @param int|null $max
     *   Maximum of characters allowed. Set to null if unlimited
     *   
     * @throws \LogicException
     *   When min is greater or equal than max
     */
    public function add(string $identifier, array $ranges, ?int $min, ?int $max): void
    {
        if(null !== $max && $min >= $max) 
            throw new \LogicException("Min cannot be greater or equal than max on '{$identifier}' range");
        
        $ranges = \implode("", $ranges);
        $this->global .= "(?=.*[{$ranges}]{0,})";
        $this->postBuild .= $ranges;
        $this->map[$identifier]["regex"] = "[{$ranges}]+";
        $this->map[$identifier]["min"] = $min ?? 1;
        $this->map[$identifier]["max"] = (null === $max) ? null : $max;
        
        // reset if calls to preg has been made
        $this->hash = [];
        $this->table = [];
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
        
        // next level WTF ! If someone wants to help me on this, i'm open :) don't want to look at this again personally
        $global = null;
        if(!$this->validate("^{$this->global}[{$this->postBuild}]+$", $string, $global))
            return $this->table[$string] = null;

        $total = \count($this);
        foreach ($this->map as $identifier => $map) {
            $matches = null;
            $this->validate($map["regex"], $global[0], $matches, true);
            $matches = \implode("", $matches[0]);
            foreach (\preg_split("//u", $matches, 0, PREG_SPLIT_NO_EMPTY) as $character)
                $this->hash[$character] = $identifier;
                
            $result = (!isset($matches[0])) ? 0 : \mb_strlen($matches);
            
            if( ($result < $map["min"]) || (null !== $map["max"] && $result > $map["max"]) )
                $total--;
        }

        return $this->table[$string] = $total;
    }
    
    /**
     * Get the range identifier of the given character.
     * This character MUST corresponds to a given one to a precedent call to preg as a hash table is generated at this moment else it will return null
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
