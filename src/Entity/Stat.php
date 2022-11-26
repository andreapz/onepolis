<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatRepository")
 */
class Stat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     */
    private $men;

    /**
     * @ORM\Column(type="integer")
     */
    private $women;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getMen(): ?int
    {
        return $this->men;
    }

    public function setMen(int $men): self
    {
        $this->men = $men;

        return $this;
    }

    public function getWomen(): ?int
    {
        return $this->women;
    }

    public function setWomen(int $women): self
    {
        $this->women = $women;

        return $this;
    }
}
