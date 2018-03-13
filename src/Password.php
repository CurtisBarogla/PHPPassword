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

namespace Zoe\Component\Password;

/**
 * Common way to describe a password
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
    private $password;
    
    /**
     * Array filled of each chars that compose the password
     * 
     * @var array
     */
    private $passwordExploded;
    
    /**
     * Initialize a new password
     * 
     * @param string $password
     *   Password value
     */
    public function __construct(string $password)
    {
        $this->password = $password;
        $this->passwordExploded = preg_split('//u', $this->password, 0, PREG_SPLIT_NO_EMPTY);
    }
    
    /**
     * Get password value
     * 
     * @return string
     *   Password value
     */
    public function getValue(): string
    {
        return $this->password;
    }
    
    /**
     * Return an array composed of each chars that compose the password
     * 
     * @return array
     *   Each chars that compose the password
     */
    public function getExplodedPassword(): array
    {
        return $this->passwordExploded;
    }
    
    /**
     * {@inheritdoc}
     * @see \Countable::count()
     */
    public function count(): int
    {
        return (\extension_loaded("mbstring")) ? mb_strlen($this->getValue()) : strlen($this->getValue());
    }
    
}
