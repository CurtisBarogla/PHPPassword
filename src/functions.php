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
 * Reassign given base with interpolated values.
 * Interpolated value key MUST respect pattern '{:key:}'
 *
 * @param string $base
 *   Base string to interpolate
 * @param string[] $values
 *   Values to interpolate indexed by the key representing it
 *
 * @return string
 *   Base interpolated with given values
 */
function interpolate(string $base, array $values): string
{
    return \str_replace(\array_map(function(string $key): string {
        return "{:{$key}:}";
    }, \array_keys($values)), $values, $base);
}

/**
 * multibyte string compatible range('A', 'Z')
 *
 * @param string $start 
 *   Character to start from (included)
 * @param string $end 
 *   Character to end with (included)
 *   
 * @return 
 *   array list of characters in unicode alphabet from $start to $end
 * 
 * @author Rodney Rehm
 * 
 * @see https://gist.github.com/rodneyrehm/1306118
 */
function mb_range(string $start, string $end): array 
{
    /*
    // if start and end are the same, well, there's nothing to do
    if ($start == $end) {
        return array($start);
    }
    */
    $_result = array();
    // get unicodes of start and end
    list(, $_start, $_end) = unpack("N*", mb_convert_encoding($start . $end, "UTF-32BE", "UTF-8"));
    // determine movement direction
    $_offset = $_start < $_end ? 1 : -1;
    $_current = $_start;
    while ($_current != $_end) {
        $_result[] = mb_convert_encoding(pack("N*", $_current), "UTF-8", "UTF-32BE");
        $_current += $_offset;
    }
    $_result[] = $end;
    return $_result;
}
