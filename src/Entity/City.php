<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
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
    private $name;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $Province;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ChiefTown;

    /**
     * @ORM\Column(type="string", length=7, nullable=true)
     */
    private $Cap;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $CC;

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

    public function getProvince(): ?string
    {
        return $this->Province;
    }

    public function setProvince(string $Province): self
    {
        $this->Province = $Province;

        return $this;
    }

    public function getChiefTown(): ?bool
    {
        return $this->ChiefTown;
    }

    public function setChiefTown(bool $ChiefTown): self
    {
        $this->ChiefTown = $ChiefTown;

        return $this;
    }

    public function getCap(): ?string
    {
        return $this->Cap;
    }

    public function setCap(?string $Cap): self
    {
        $this->Cap = $Cap;

        return $this;
    }

    public function getCC(): ?string
    {
        return $this->CC;
    }

    public function setCC(?string $CC): self
    {
        $this->CC = $CC;

        return $this;
    }
}
