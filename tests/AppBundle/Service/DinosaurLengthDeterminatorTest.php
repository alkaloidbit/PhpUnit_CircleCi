<?php

namespace AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;

/**
 * Class DinosaurLengthDeterminatorTest
 * @author yourname
 */
class DinosaurLengthDeterminatorTest extends TestCase
{
    /**
     * undocumented function
     * @dataProvider getSpecLengthTests
     * @return void
     */
    public function testItReturnsCorrectLengthRange($spec, $minExpectedSize, $maxExpectedSize)
    {
        $determinator = new DinosaurLengthDeterminator();
        $actualSize = $determinator->getLengthFromSpecification($spec);

        $this->assertGreaterThanOrEqual($minExpectedSize, $actualSize);
        $this->assertLessThanOrEqual($maxExpectedSize, $actualSize);
    }
     
    /**
     * undocumented function
     *
     * @return void
     */
    public function getSpecLengthTests()
    {
        return [
            // specification, min length, max length
            ['large carnivorous dinosaur',  Dinosaur::LARGE, Dinosaur::HUGE -1],
            'default response' => ['give me all the cookies!!!', 0, Dinosaur::LARGE - 1],
            ['large herbivore', Dinosaur::LARGE, Dinosaur::HUGE - 1],    
            ['huge dinosaur', Dinosaur::HUGE, 100],
            ['huge dino', Dinosaur::HUGE, 100],
            ['huge', Dinosaur::HUGE, 100],
            ['OMG', Dinosaur::HUGE, 100],
            ['😱', Dinosaur::HUGE, 100],
            
        ];
    }
    
    
}
