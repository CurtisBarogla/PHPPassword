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
 * Make a component aware of a RegexRange.
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
interface RegexRangeAwareInterface
{
    
    /**
     * Get setted range
     * 
     * @return RegexRange
     *   Range setted
     */
    public function getRange(): RegexRange;
    
    /**
     * Link a range to the component
     * 
     * @param RegexRange $range
     *   Range to link
     */
    public function setRange(RegexRange $range): void;
    
}
