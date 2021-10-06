<?php

namespace AppBundle\Entity;

use AppBundle\Exception\DinosaursAreRunningRampantException;
use AppBundle\Exception\NotABuffetException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Enclosure
 * @author yourname
 * @ORM\Entity
 * @ORM\Table(name="enclosures")
 */
class Enclosure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Dinosaur", mappedBy="enclosure", cascade={"persist"})
     */
    private $dinosaurs;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Security", mappedBy="enclosure", cascade={"persist"})
     */
    private $securities;

    /**
     * @param 
     */
    public function __construct(bool $withBasicSecurity = false)
    {
        $this->dinosaurs = new ArrayCollection();
        $this->securities = new ArrayCollection();

        if ($withBasicSecurity) {
            $this->addSecurity(new Security('Fence', true, $this));
        } 
    }
    
    /**
     * undocumented function
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * undocumented function
     *
     * @return int
     */
    public function getDinosaurCount(): int
    {
        return $this->dinosaurs->count();
    }
    
    
    /**
     * undocumented function
     *
     * @return void
     */
    public function getDinosaurs(): Collection
    {
        return $this->dinosaurs;
    }
    

    /**
     * undocumented function
     *
     * @return void
     */
    public function addDinosaur(Dinosaur $dinosaur)
    {
        if (!$this->isSecurityActive()) {
            throw new DinosaursAreRunningRampantException('Are you Craaazy?!?');
        }

        if (!$this->canAddDinosaur($dinosaur)) {
            throw new NotABuffetException();
        }

        $this->dinosaurs[] = $dinosaur;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function isSecurityActive(): bool
    {
        foreach ($this->securities as $security) {
            if ($security->getIsActive()) {
                return true;
            }
        }
        return false;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function addSecurity(Security $security)
    {
        $this->securities[] = $security;
    }

    /**
     * undocumented function
     *
     * @return void
     */
    public function getSecurities(): Collection
    {
        return $this->securities;
    }
    
    
    /**
     * undocumented function
     *
     * @return void
     */
    private function canAddDinosaur(Dinosaur $dinosaur): bool
    {
        return count($this->dinosaurs) === 0 
            || $this->dinosaurs->first()->isCarnivorous() === $dinosaur->isCarnivorous();
    }
    
}
