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
 * Communicate with all password components.
 * Represent a simple password
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class Password implements \Countable
{
    
    /**
     * Password value
     * 
     * @var string
     */
    private $value;
    
    /**
     * All characters
     * 
     * @var array[string]|null
     */
    private $exploded;
    
    /**
     * Initialize a new password
     * 
     * @param string $value
     *   Password value
     */
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    
    /**
     * Get password value
     * 
     * @return string
     *   Password value
     */
    public function get(): string
    {
        return $this->value;
    }
    
    /**
     * Get an exploded representation of the password
     * 
     * @return array
     *   Each characters composing the password value
     */
    public function getExploded(): array
    {
        return $this->exploded ?? $this->exploded = \preg_split("//u", $this->value, 0, PREG_SPLIT_NO_EMPTY);
    }
    
    /**
     * {@inheritDoc}
     * @see \Countable::count()
     */
    public function count()
    {
        return \count($this->getExploded());
    }
    
}
