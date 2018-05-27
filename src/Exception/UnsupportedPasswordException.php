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

namespace Ness\Component\Password\Exception;

/**
 * When a password cannot be handled by a component
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UnsupportedPasswordException extends \InvalidArgumentException
{
    //    
}
