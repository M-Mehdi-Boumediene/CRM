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
     * @ORM\Column(type="string", length=255, nullable=true, unique=false)
     */
    private $note;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etudiantid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moduleid;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $intervenantid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $moyenne;

    /**
     * @ORM\ManyToOne(targetEntity=Modules::class, inversedBy="notes")
     */
    private $module;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $blocid;

    /**
     * @ORM\ManyToOne(targetEntity=Blocs::class, inversedBy="notes")
     */
    private $bloc;

    /**
     * @ORM\ManyToOne(targetEntity=Classes::class, inversedBy="notes")
     */
    private $classes;

    /**
     * @ORM\ManyToMany(targetEntity=TableauNotes::class, inversedBy="notes",cascade={"all"})
     */
    private $tableau;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $semestre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coefficient;

    public function __construct()
    {
        $this->tableau = new ArrayCollection();
    }





    

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

    public function getEtudiantid(): ?string
    {
        return $this->etudiantid;
    }

    public function setEtudiantid(?string $etudiantid): self
    {
        $this->etudiantid = $etudiantid;

        return $this;
    }

    public function getModuleid(): ?string
    {
        return $this->moduleid;
    }

    public function setModuleid(?string $moduleid): self
    {
        $this->moduleid = $moduleid;

        return $this;
    }

    public function getIntervenantid(): ?string
    {
        return $this->intervenantid;
    }

    public function setIntervenantid(?string $intervenantid): self
    {
        $this->intervenantid = $intervenantid;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getMoyenne(): ?string
    {
        return $this->moyenne;
    }

    public function setMoyenne(?string $moyenne): self
    {
        $this->moyenne = $moyenne;

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

    public function getBlocid(): ?string
    {
        return $this->blocid;
    }

    public function setBlocid(?string $blocid): self
    {
        $this->blocid = $blocid;

        return $this;
    }

    public function getBloc(): ?Blocs
    {
        return $this->bloc;
    }

    public function setBloc(?Blocs $bloc): self
    {
        $this->bloc = $bloc;

        return $this;
    }

    public function getClasses(): ?Classes
    {
        return $this->classes;
    }

    public function setClasses(?Classes $classes): self
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @return Collection<int, TableauNotes>
     */
    public function getTableau(): Collection
    {
        return $this->tableau;
    }

    public function addTableau(TableauNotes $tableau): self
    {
        if (!$this->tableau->contains($tableau)) {
            $this->tableau[] = $tableau;
        }

        return $this;
    }

    public function removeTableau(TableauNotes $tableau): self
    {
        $this->tableau->removeElement($tableau);

        return $this;
    }

    public function getSemestre(): ?string
    {
        return $this->semestre;
    }

    public function setSemestre(?string $semestre): self
    {
        $this->semestre = $semestre;

        return $this;
    }

    public function getCoefficient(): ?string
    {
        return $this->coefficient;
    }

    public function setCoefficient(?string $coefficient): self
    {
        $this->coefficient = $coefficient;

        return $this;
    }



    
}