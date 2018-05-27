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

namespace NessTest\Component\Password;

use Ness\Component\Password\RegexRange;

/**
 * RegexRange testcase
 * 
 * @see \Ness\Component\Password\RegexRange
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class RegexRangeTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\RegexRange::getIdentifier()
     */
    public function testGetIdentifier(): void
    {
        $range = new RegexRange("Foo");
        
        $this->assertSame("Foo", $range->getIdentifier());
        
        $range->add("Foo", ["Foo-Bar"], null, null);
        $range->add("Bar", ["Bar-Foo"], null, null);
        
        $this->assertSame("Foo", $range->getIdentifier());
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::getRanges()
     */
    public function testGetRanges(): void 
    {
        $range = new RegexRange("Foo");
        $range->add("foo", ["a-z"], null, 4);
        $range->add("bar", ["A-Z"], 4, null);
        
        $this->assertSame([
            "foo" => ["regex" => "[a-z]+", "min" => 1, "max" => 4],
            "bar" => ["regex" => "[A-Z]+", "min" => 4, "max" => null]
        ], $range->getRanges());
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testAdd(): void
    {
        $range = new RegexRange("Foo");
        
        $this->assertNull($range->add("foo", ["A-Z"], 1, null));
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::preg()
     */
    public function testPreg(): void
    {
        $range = new RegexRange("Foo");
        
        $range->add("foo", ["a-z"], null, null);
        $range->add("bar", ["A-Z"], null, null);
        
        $this->assertSame(2, $range->preg("Foo"));
        $this->assertSame(1, $range->preg("foo"));
        $this->assertSame(1, $range->preg("FOO"));
        $this->assertSame(1, $range->preg("FOO"));
        $this->assertNull($range->preg("*"));
        
        $range = new RegexRange("Foo");
        $range->add("foo", ["a-z"], 2, null);
        $range->add("bar", ["A-Z"], null, 4);
        
        $this->assertSame(2, $range->preg("fooBAR"));
        $this->assertSame(1, $range->preg("fooBAREE"));
        $this->assertSame(0, $range->preg("fBAREE"));
        $this->assertNull($range->preg("*"));
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::pregRange()
     */
    public function testPregRange(): void
    {
        $range = new RegexRange("Foo");
        
        $range->add("foo", ["a-z"], null, null);
        $range->add("bar", ["A-Z"], null, null);
        
        $this->assertNull($range->pregRange("o"));
        $this->assertNull($range->pregRange("F"));
        
        $range->preg("Fo");
        
        $this->assertSame("foo", $range->pregRange("o"));
        $this->assertSame("bar", $range->pregRange("F"));
        $this->assertNull($range->pregRange("a"));
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::count()
     */
    public function testCount(): void
    {
        $range = new RegexRange("Foo");
        
        $this->assertSame(0, \count($range));
        
        $range->add("Foo", ["Foo"], 0, 1);
        $range->add("Bar", ["Bar"], null, null);
        
        $this->assertSame(2, \count($range));
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testExceptionWhenMinIsGreaterThanMax(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Min cannot be greater or equal than max on 'Foo' range");
        
        $range = new RegexRange("Foo");
        
        $range->add("Foo", ["Foo"], 10, 1);
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testExceptionWhenMinIsEqualMax(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Min cannot be greater or equal than max on 'Foo' range");
        
        $range = new RegexRange("Foo");
        
        $range->add("Foo", ["Foo"], 10, 10);
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::preg()
     */
    public function testExceptionWhenRegexCannotBeCompiled(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("This regex '#(*UTF8)^(?=.*[a--z]{0,})[a--z]+$#' cannot be compiled by preg_match_*");
        
        $range = new RegexRange("Foo");
        $range->add("foo", ["a--z"], null, null);
        
        @$range->preg("foo");
    }
    
}
