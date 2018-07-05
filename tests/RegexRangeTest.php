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
        $expected = "631987fda2f494fce7040b15f7f5388558deb31d";
        $range = new RegexRange();

        $range->add("Foo", ["A-C", "D-M", "N-Z"]);
        $range->add("Bar", ["🍕-🍘"]);
        
        $this->assertSame($expected, $range->getIdentifier());
        $this->assertSame($expected, $range->getIdentifier());
        
        $range = new RegexRange();
        
        $range->add("Bar", ["🍕-🍘"]);
        $range->add("Foo", ["D-M", "A-C", "N-Z"]);
        
        $this->assertSame($expected, $range->getIdentifier());
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::getRanges()
     */
    public function testGetRanges(): void 
    {
        $range = new RegexRange();
        
        $range->add("foo", ["a-z"], null, 4);
        $range->add("bar", ["A-Z"], 2, 10);
        $range->add("moz", ["🍕-🍘"]);
        
        $this->assertSame([
            "bar"   =>  [
                "list"  => "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
                "min"   =>  2,
                "max"   =>  10
            ],
            "foo"   =>  [
                "list"  =>  "abcdefghijklmnopqrstuvwxyz",
                "min"   =>  1,
                "max"   =>  4
            ],
            "moz"   =>  [
                "list"  =>  "🍕🍖🍗🍘",
                "min"   =>  1,
                "max"   =>  null
            ]
        ], $range->getRanges());
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::getList()
     */
    public function testGetList(): void
    {
        $range = new RegexRange();
        $range->add("foo", ["a-z"], null, 4);
        $range->add("bar", ["A-Z"], 4, null);
        $range->add("miam", ["🍕-🍘"]);
        
        $this->assertSame(\preg_split("//u", "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ🍕🍖🍗🍘", 0, PREG_SPLIT_NO_EMPTY), $range->getList());
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testAdd(): void
    {
        $range = new RegexRange();
        
        $this->assertNull($range->add("foo", ["A-Z"], 1, null));
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::preg()
     */
    public function testPreg(): void
    {
        $range = new RegexRange();
        $range->add("foo", ["a-z"], null, 4);
        $range->add("bar", ["A-Z"], 4, null);
        $range->add("miam", ["🍕-🍘"]);
        
        $this->assertNull($range->preg("éfoo"));
        $this->assertSame(0, $range->preg("foobare"));
        $this->assertSame(1, $range->preg("foo"));
        $this->assertSame(1, $range->preg("FOObare"));
        $this->assertSame(2, $range->preg("FOOObare"));
        $this->assertSame(3, $range->preg("FOOBare🍕"));
        $this->assertSame(3, $range->preg("FOOBare🍕"));
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::pregRange()
     */
    public function testPregRange(): void
    {
        $range = new RegexRange("Foo");
        $range->add("foo", ["a-z"], null, 4);
        $range->add("bar", ["A-Z"], 4, null);
        $range->add("miam", ["🍕-🍘"]);
        
        $this->assertSame("foo", $range->pregRange('f'));
        $this->assertNull($range->pregRange('é'));
        $this->assertSame("bar", $range->pregRange('T'));
        $this->assertSame("miam", $range->pregRange('🍖'));
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::count()
     */
    public function testCount(): void
    {
        $range = new RegexRange("Foo");
        
        $this->assertSame(0, \count($range));
        
        $range->add("foo", ["a-z"]);
        
        $this->assertSame(1, \count($range));
    }
    
                    /**_____EXCEPTIONS_____**/
    
    /**
     * @see \Ness\Component\Password\RegexRange::getIdentifier()
     */
    public function testExceptionGetIdentifierWhenNoRangeHasBeenSetted(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Impossible to get the identifier of an empty RegexRange");
        
        $range = new RegexRange();
        
        $range->getIdentifier();
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testExceptionAddWhenAnIdentifierIsAlreadyRegisteredIntoTheRegexRange(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("This identifier 'foo' has been already setted into '1ed097432a73b32d6708c5fd0f5e0ecc51fad1b2' regex range");
        
        $range = new RegexRange();
        
        $range->add("foo", ["a-z"]);
        $range->add("foo", ["A-Z"]);
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testExceptionAddWhenACharacterHasBeenAlreadySettedIntoARangeList(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("This character 'f' has been already registered under 'foo' identifier");
        
        $range = new RegexRange();
        
        $range->add("foo", ["a-z"]);
        $range->add("bar", ["f-z"]);
    }
    
    /**
     * @see \Ness\Component\Password\RegexRange::add()
     */
    public function testExceptionAddWhenPatternRangeIsInvalid(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage("Range 'a-zz' on 'foo' identifier MUST respect pattern : 'char_start'-'char-end'");
        
        $range = new RegexRange();
        
        $range->add("foo", ["a-zz"]);
    }
    
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
        $this->expectExceptionMessage("This regex '#(*UTF8)^[]+$#' cannot be compiled by preg_match_*");
        
        $range = new RegexRange("Foo");
        try {
            $range->add("foo", ["a--z"], null, null);            
        } catch (\LogicException $e) {
            @$range->preg("foo");
        }
    }
    
}
