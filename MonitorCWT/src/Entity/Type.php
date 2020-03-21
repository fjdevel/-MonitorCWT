<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TypeRepository")
 */
class Type
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterfaceDevice", mappedBy="type")
     */
    private $interfaceDevices;

    public function __construct()
    {
        $this->interfaceDevices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|InterfaceDevice[]
     */
    public function getInterfaceDevices(): Collection
    {
        return $this->interfaceDevices;
    }

    public function addInterfaceDevice(InterfaceDevice $interfaceDevice): self
    {
        if (!$this->interfaceDevices->contains($interfaceDevice)) {
            $this->interfaceDevices[] = $interfaceDevice;
            $interfaceDevice->setType($this);
        }

        return $this;
    }

    public function removeInterfaceDevice(InterfaceDevice $interfaceDevice): self
    {
        if ($this->interfaceDevices->contains($interfaceDevice)) {
            $this->interfaceDevices->removeElement($interfaceDevice);
            // set the owning side to null (unless already changed)
            if ($interfaceDevice->getType() === $this) {
                $interfaceDevice->setType(null);
            }
        }

        return $this;
    }
}
