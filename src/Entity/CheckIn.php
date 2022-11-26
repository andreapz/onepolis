<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CheckInRepository")
 */
class CheckIn
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Citizen", inversedBy="checkins")
     * @ORM\JoinColumn(name="citizen", referencedColumnName="id")
     */
    private $citizen;

    /**
     * @ORM\Column(type="boolean")
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $checkDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $uid;

    
    public function create($citizen, $type, $uid) { 
        $check = new CheckIn();
        $check->setCitizen($citizen);
        $check->setType($type);
        $check->setCheckDate(new \DateTime("now"));
        $check->setUid($uid);
        return $check;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCitizen(): ?Citizen
    {
        return $this->citizen;
    }

    public function setCitizen(?Citizen $citizen): self
    {
        $this->citizen = $citizen;

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(bool $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCheckDate(): ?\DateTimeInterface
    {
        return $this->checkDate;
    }

    public function setCheckDate(\DateTimeInterface $checkDate): self
    {
        $this->checkDate = $checkDate;

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
}
