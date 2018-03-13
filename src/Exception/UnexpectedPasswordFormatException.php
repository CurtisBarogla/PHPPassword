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

namespace Zoe\Component\Password\Exception;

/**
 * When a password form is invalid over a topology generator
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class UnexpectedPasswordFormatException extends \UnexpectedValueException
{
    //
}
