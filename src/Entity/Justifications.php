<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\JustificationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=JustificationsRepository::class)
 */
#[ApiResource]
class Justifications
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="justifications")
     */
    private $user;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity=Absences::class, inversedBy="justifications")
     */
    private $absence;

    /**
     * @ORM\ManyToOne(targetEntity=TableauAbsences::class, inversedBy="justifications")
     */
    private $tableauabsence;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getAbsence(): ?Absences
    {
        return $this->absence;
    }

    public function setAbsence(?Absences $absence): self
    {
        $this->absence = $absence;

        return $this;
    }

    public function getTableauabsence(): ?TableauAbsences
    {
        return $this->tableauabsence;
    }

    public function setTableauabsence(?TableauAbsences $tableauabsence): self
    {
        $this->tableauabsence = $tableauabsence;

        return $this;
    }
}
