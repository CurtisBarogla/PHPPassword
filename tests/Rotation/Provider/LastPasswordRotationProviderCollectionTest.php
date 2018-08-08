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

namespace NessTest\Component\Password\Rotation\Provider;

use NessTest\Component\Password\PasswordTestCase;
use Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderInterface;
use Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderCollection;
use Ness\Component\User\UserInterface;

/**
 * LastPasswordRotationProviderCollection testcase
 * 
 * @see \Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderCollection
 * 
 * @author CurtisBarogla <curtis_barogla@outlook.fr>
 *
 */
class LastPasswordRotationProviderCollectionTest extends PasswordTestCase
{
    
    /**
     * @see \Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderCollection::addProvider()
     */
    public function testAddProvider(): void
    {
        $provider = $this->getMockBuilder(LastPasswordRotationProviderInterface::class)->getMock();
        
        $collection = new LastPasswordRotationProviderCollection();
        
        $this->assertNull($collection->addProvider($provider));
    }
    
    /**
     * @see \Ness\Component\Password\Rotation\Provider\LastPasswordRotationProviderCollection::provide()
     */
    public function testProvide(): void
    {
        $rotation = new \DateTimeImmutable("NOW - 42 days");
        
        $collection = new LastPasswordRotationProviderCollection();
        
        $this->assertNull($collection->provide($this->getMockBuilder(UserInterface::class)->getMock()));
        
        $user = $this->getMockBuilder(UserInterface::class)->getMock();
        
        $providerFoo = $this->getMockBuilder(LastPasswordRotationProviderInterface::class)->getMock();
        $providerFoo->expects($this->exactly(3))->method("provide")->withConsecutive([$user])->will($this->onConsecutiveCalls(null, $rotation, null));
        $providerBar = $this->getMockBuilder(LastPasswordRotationProviderInterface::class)->getMock();
        $providerBar->expects($this->exactly(2))->method("provide")->withConsecutive([$user])->will($this->onConsecutiveCalls($rotation, null));
        
        $collection->addProvider($providerFoo);
        $collection->addProvider($providerBar);
        
        $this->assertEquals($rotation->format("d/m/Y"), $collection->provide($user)->format("d/m/Y"));
        $this->assertEquals($rotation->format("d/m/Y"), $collection->provide($user)->format("d/m/Y"));
        $this->assertNull($collection->provide($user));
    }
    
}
