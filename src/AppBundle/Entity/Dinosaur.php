<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dinosaurs")
 */
class Dinosaur
{
    const LARGE = 10;
    const HUGE = 30;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $length = 0;

    private $genus;

    private $isCarnivorous;

    /**
     * @var Enclosure
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enclosure", inversedBy="dinosaurs")
     */
    private $enclosure;

    public function __construct(string $genus = 'Unknown', bool $isCarnivorous = false)
    {
        $this->genus = $genus;
        $this->isCarnivorous = $isCarnivorous;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length)
    {
        $this->length = $length;
    }

    public function getSpecification(): string 
    {
        return sprintf(
            'The %s %scarnivorous dinosaur is %d meters long',
            $this->genus,
            $this->isCarnivorous ? '': 'non-',
            $this->length
        );
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    public function isCarnivorous(): bool
    {
        return $this->isCarnivorous;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function setEnclosure(Enclosure $enclosure)
    {
        $this->enclosure = $enclosure;
    }
}