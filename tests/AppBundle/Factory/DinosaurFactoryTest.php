<?php

namespace Tests\AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\DinosaurLengthDeterminator;
use PHPUnit\Framework\TestCase;

/**
 * Class DinosaurFactoryTest
 * @author yourname
 */
class DinosaurFactoryTest extends TestCase
{
    /**
     *
     * @var DinosaurFactory
     */
    private $factory;

    private $lengthDeterminator;


    public function setUp()
    {
        /** 
         * @var \PHPUnit_Framework_MockObject_MockObject&\AppBundle\Service\DinosaurLengthDeterminator $lengthDeterminator
         */
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function testItGrowsALargeVelociraptor()
    {
        $dinosaur = $this->factory->growVelociraptor(5);

        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertInternalType('string', $dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }

    public function testItGrowsaTriceraptor()
    {
        $this->markTestIncomplete('Watiting for confirmation from GenLab');
    }

    public function testItGGrowsABabyVelociraptor()
    {
        if (!class_exists('Nannny')) {
            $this->markTestSkipped('There is nobody to watch the baby!');
        }

        $dinosaur = new $this->factory->growVelociraptor(1);

        $this->assertSame(1, $dinosaur->getLength());
    }

    /**
     * Undocumented function
     *
     * @param string $spec
     * @param boolean $expectedIsLarge
     * @param boolean $expectedIsCarnivorous
     * @dataProvider getSpecificationTests
     * @return void
     */
    public function testItGrowsADinosaurFromSpecification(string $spec, bool $expectedIsCarnivorous)
    {
        $this->lengthDeterminator->expects($this->once())
             ->method('getLengthFromSpecification')
             ->with($spec)
            ->willReturn(20);

        $dinosaur = $this->factory->growFromSpecification($spec); 

        $this->assertSame($expectedIsCarnivorous, $dinosaur->isCarnivorous(), 'Diets do not match');
        $this->assertSame(20, $dinosaur->getLength());
    }

    public function getSpecificationTests()
    {
        return [
            // specification, is large, is carnivorous
            ['large carnivorous dinosaur', true],
            ['give me all the cookies!!!', false],
            ['large herbivore', false],
        ];
    }
}
