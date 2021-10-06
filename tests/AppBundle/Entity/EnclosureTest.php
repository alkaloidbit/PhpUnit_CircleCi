<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Exception\DinosaursAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class EnclosureTest
 * @author yourname
 */
class EnclosureTest extends TestCase
{
    /**
     * 
     * @var Enclosure
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enclosure", inversedBy="dinosaurs")
     */
    private $enclosure;

    /**
     * undocumented function
     *
     * @return void
     */
    public function testItHasNoDinosaursByDefault()
    {
        $enclosure = new Enclosure();

        // $this->assertCount(0, $enclosure->getDinosaurs());
        $this->assertEmpty($enclosure->getDinosaurs());
    }
    
    /**
     * 
     * undocumented function
     *
     * @return void
     */
    public function testItDoesNotAllowCarnivorousDinosToMixWithHerbivores()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur());
        $this->expectException(NotABuffetException::class);
        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
    }

    /**
     * undocumented function
     *
     * @expectedException \AppBundle\Exception\NotABuffetException
     * @return void
     */
    public function testItDoesNotAllowToAddNonCarnivorousDinosaursToCarnivorousEnclosure()
    {
        $enclosure = new Enclosure(true);

        $enclosure->addDinosaur(new Dinosaur('Velociraptor', true));
        $enclosure->addDinosaur(new Dinosaur());
    }
    
    /**
     * undocumented function
     *
     * @return void
     */
    public function testItDoesNotAllowToAddDinosToUnsecureEnclosures()
    {
        $enclosure = new Enclosure();

        $this->expectException(DinosaursAreRunningRampantException::class);
        $this->expectExceptionMessage('Are you Craaazy?!?');

        $enclosure->addDinosaur(new Dinosaur());
    }
    
    
    /**
     * undocumented function
     *
     * @return void
     */
    public function testItAddsDinosaurs()
    {
        $enclosure = new Enclosure(true);
        $enclosure->addDinosaur(new Dinosaur());
        $enclosure->addDinosaur(new Dinosaur());

        $this->assertCount(2, $enclosure->getDinosaurs());
    }
}

