<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterfaceDeviceRepository")
 */
class InterfaceDevice
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
     * @ORM\Column(type="string", length=10)
     */
    private $channel;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="interfaceDevices")
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Registry", mappedBy="interface")
     */
    private $registries;

    public function __construct()
    {
        $this->registries = new ArrayCollection();
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

    public function getChannel(): ?string
    {
        return $this->channel;
    }

    public function setChannel(string $channel): self
    {
        $this->channel = $channel;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection|Registry[]
     */
    public function getRegistries(): Collection
    {
        return $this->registries;
    }

    public function addRegistry(Registry $registry): self
    {
        if (!$this->registries->contains($registry)) {
            $this->registries[] = $registry;
            $registry->setInterface($this);
        }

        return $this;
    }

    public function removeRegistry(Registry $registry): self
    {
        if ($this->registries->contains($registry)) {
            $this->registries->removeElement($registry);
            // set the owning side to null (unless already changed)
            if ($registry->getInterface() === $this) {
                $registry->setInterface(null);
            }
        }

        return $this;
    }
}
