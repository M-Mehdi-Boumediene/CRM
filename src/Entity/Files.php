<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FilesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=FilesRepository::class)
 */
class Files
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
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Modules::class, inversedBy="files")
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Telechargements::class, inversedBy="files")
     */
    private $telechargements;

    /**
     * @ORM\ManyToOne(targetEntity=TableauNotes::class, inversedBy="copie")
     */
    private $tableauNotes;

    /**
     * @ORM\ManyToOne(targetEntity=TableauAbsences::class, inversedBy="copie")
     */
    private $tableauAbsences;

  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getModule(): ?Modules
    {
        return $this->module;
    }

    public function setModule(?Modules $module): self
    {
        $this->module = $module;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getTelechargements(): ?Telechargements
    {
        return $this->telechargements;
    }

    public function setTelechargements(?Telechargements $telechargements): self
    {
        $this->telechargements = $telechargements;

        return $this;
    }

    public function getTableauNotes(): ?TableauNotes
    {
        return $this->tableauNotes;
    }

    public function setTableauNotes(?TableauNotes $tableauNotes): self
    {
        $this->tableauNotes = $tableauNotes;

        return $this;
    }

    public function getTableauAbsences(): ?TableauAbsences
    {
        return $this->tableauAbsences;
    }

    public function setTableauAbsences(?TableauAbsences $tableauAbsences): self
    {
        $this->tableauAbsences = $tableauAbsences;

        return $this;
    }


}
