<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceRepository")
 */
class Device
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $deviceId;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="devices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

     /**
     * @ORM\OneToMany(targetEntity="App\Entity\InterfaceDevice", mappedBy="device", orphanRemoval=true)
     */
    private $interfaces;

    public function __construct()
    {
        $this->interfaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    public function setDeviceId(string $deviceId): self
    {
        $this->deviceId = $deviceId;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|InterfaceDevice[]
     */
    public function getInterfaces(): Collection
    {
        return $this->interfaces;
    }

    public function addInterface(InterfaceDevice $interface): self
    {
        if (!$this->interfaces->contains($interface)) {
            $this->interfaces[] = $interface;
            $interface->setDevice($this);
        }

        return $this;
    }

    public function removeInterface(InterfaceDevice $interface): self
    {
        if ($this->interfaces->contains($interface)) {
            $this->interfaces->removeElement($interface);
            // set the owning side to null (unless already changed)
            if ($interface->getDevice() === $this) {
                $interface->setDevice(null);
            }
        }

        return $this;
    }
}
