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

namespace Ness\Component\Password\Traits;

use Ness\Component\Password\RegexRange;

/**
 * Shortcut for making a component aware of a RegexRange
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
trait RegexRangeAwareTrait
{
    
    /**
     * Range setted
     * 
     * @var RegexRange
     */
    private $range;
    
    /**
     * {@inheritdoc}
     * @see \Ness\Component\Password\RegexRangeAwareInterface::getRange()
     */
    public function getRange(): RegexRange
    {
        return $this->range;
    }
    
    /**
     * {@inheritdoc}
     * @see \Ness\Component\Password\RegexRangeAwareInterface::setRange()
     */
    public function setRange(RegexRange $range): void
    {
        $this->range = $range;
    }
    
}
