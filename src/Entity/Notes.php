<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NotesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=NotesRepository::class)
 */
class Notes
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
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coefmodule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coefbloc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moy;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moygeneral;

    /**
     * @ORM\OneToOne(targetEntity=Modules::class, cascade={"persist", "remove"})
     */
    private $module;

    /**
     * @ORM\OneToOne(targetEntity=Etudiants::class, cascade={"persist", "remove"})
     */
    private $apprenant;

    /**
     * @ORM\OneToOne(targetEntity=Intervenants::class, cascade={"persist", "remove"})
     */
    private $Intervenant;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getCoefmodule(): ?string
    {
        return $this->coefmodule;
    }

    public function setCoefmodule(?string $coefmodule): self
    {
        $this->coefmodule = $coefmodule;

        return $this;
    }

    public function getCoefbloc(): ?string
    {
        return $this->coefbloc;
    }

    public function setCoefbloc(?string $coefbloc): self
    {
        $this->coefbloc = $coefbloc;

        return $this;
    }

    public function getMoy(): ?string
    {
        return $this->moy;
    }

    public function setMoy(?string $moy): self
    {
        $this->moy = $moy;

        return $this;
    }

    public function getMoygeneral(): ?string
    {
        return $this->moygeneral;
    }

    public function setMoygeneral(?string $moygeneral): self
    {
        $this->moygeneral = $moygeneral;

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

    public function getApprenant(): ?Etudiants
    {
        return $this->apprenant;
    }

    public function setApprenant(?Etudiants $apprenant): self
    {
        $this->apprenant = $apprenant;

        return $this;
    }

    public function getIntervenant(): ?Intervenants
    {
        return $this->Intervenant;
    }

    public function setIntervenant(?Intervenants $Intervenant): self
    {
        $this->Intervenant = $Intervenant;

        return $this;
    }

    

    
}
