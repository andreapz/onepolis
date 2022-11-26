<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HotelMatchRepository")
 */
class HotelMatch
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
    private $citizen;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $roomreal;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $uid;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $d;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $last;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note;
    
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCitizen(): ?int
    {
        return $this->citizen;
    }

    public function setCitizen(int $citizen): self
    {
        $this->citizen = $citizen;

        return $this;
    }

    public function getRoomreal(): ?int
    {
        return $this->roomreal;
    }

    public function setRoomreal(int $roomreal): self
    {
        $this->roomreal = $roomreal;

        return $this;
    }

    public function getUid(): ?int
    {
        return $this->uid;
    }

    public function setUid(int $uid): self
    {
        $this->uid = $uid;

        return $this;
    }

    public function getD(): ?int
    {
        return $this->d;
    }

    public function setD(int $d): self
    {
        $this->d = $d;

        return $this;
    }

    public function getUpdate(): ?\DateTimeInterface
    {
        return $this->update;
    }

    public function setUpdate(\DateTimeInterface $update): self
    {
        $this->update = $update;

        return $this;
    }

    public function getLast(): ?\DateTimeInterface
    {
        return $this->last;
    }

    public function setLast(\DateTimeInterface $last): self
    {
        $this->last = $last;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }
    
    
}
