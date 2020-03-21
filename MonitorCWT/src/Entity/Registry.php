<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistryRepository")
 */
class Registry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     */
    private $value;

    /**
     * @ORM\Column(type="datetime")
     */
    private $rectime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InterfaceDevice", inversedBy="registries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $interface;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getRectime(): ?\DateTimeInterface
    {
        return $this->rectime;
    }

    public function setRectime(\DateTimeInterface $rectime): self
    {
        $this->rectime = $rectime;

        return $this;
    }

    public function getInterface(): ?InterfaceDevice
    {
        return $this->interface;
    }

    public function setInterface(?InterfaceDevice $interface): self
    {
        $this->interface = $interface;

        return $this;
    }
}
