<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CitizenPaymentRepository")
 */
class CitizenPayment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $value;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $paymentDate;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $updateDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;
    
    /**
     * @var int
     *
     * @ORM\Column(name="tid", type="integer")
     */
    private $tid;
    
    /**
     * @var int
     *
     * @ORM\Column(name="uid", type="integer")
     */
    private $uid; 
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deleteDate;
    
    /**
     * @var int
     *
     * @ORM\Column(name="duid", type="integer", nullable=true)
     */
    private $duid; 
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="payments")
     * @ORM\JoinColumn(name="task", referencedColumnName="id")
     */
    private $task;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

   

    public function getPaymentDate(): ?\DateTimeInterface
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(\DateTimeInterface $paymentDate): self
    {
        $this->paymentDate = $paymentDate;

        return $this;
    }

    

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getTid(): ?int
    {
        return $this->tid;
    }

    public function setTid(int $tid): self
    {
        $this->tid = $tid;

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

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    public function getDeleteDate(): ?\DateTimeInterface
    {
        return $this->deleteDate;
    }

    public function setDeleteDate(?\DateTimeInterface $deleteDate): self
    {
        $this->deleteDate = $deleteDate;

        return $this;
    }

    public function getDuid(): ?int
    {
        return $this->duid;
    }

    public function setDuid(?int $duid): self
    {
        $this->duid = $duid;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
