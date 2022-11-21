<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TableauAbsencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauAbsencesRepository::class)
 */
#[ApiResource]
class TableauAbsences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Etudiants::class, inversedBy="tableauAbsences")
     */
    private $etudiant;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateabsence;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $retard;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="tableauAbsences")
     */
    private $copie;

    /**
     * @ORM\ManyToMany(targetEntity=Absences::class, mappedBy="tableau")
     */
    private $absences;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $presence;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $absence;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $du;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $au;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enretard;

    /**
     * @ORM\ManyToMany(targetEntity=Absintervenants::class, mappedBy="tableau")
     */
    private $absintervenants;



    public function __construct()
    {
        $this->etudiant = new ArrayCollection();
        $this->copie = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->absintervenants = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Etudiants>
     */
    public function getEtudiant(): Collection
    {
        return $this->etudiant;
    }

    public function addEtudiant(Etudiants $etudiant): self
    {
        if (!$this->etudiant->contains($etudiant)) {
            $this->etudiant[] = $etudiant;
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): self
    {
        $this->etudiant->removeElement($etudiant);

        return $this;
    }

    public function getDateabsence(): ?\DateTimeInterface
    {
        return $this->dateabsence;
    }

    public function setDateabsence(?\DateTimeInterface $dateabsence): self
    {
        $this->dateabsence = $dateabsence;

        return $this;
    }

    public function getRetard(): ?\DateTimeInterface
    {
        return $this->retard;
    }

    public function setRetard(?\DateTimeInterface $retard): self
    {
        $this->retard = $retard;

        return $this;
    }

    /**
     * @return Collection<int, Files>
     */
    public function getCopie(): Collection
    {
        return $this->copie;
    }

    public function addCopie(Files $copie): self
    {
        if (!$this->copie->contains($copie)) {
            $this->copie[] = $copie;
            $copie->setTableauAbsences($this);
        }

        return $this;
    }

    public function removeCopie(Files $copie): self
    {
        if ($this->copie->removeElement($copie)) {
            // set the owning side to null (unless already changed)
            if ($copie->getTableauAbsences() === $this) {
                $copie->setTableauAbsences(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Absences>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absences $absence): self
    {
        if (!$this->absences->contains($absence)) {
            $this->absences[] = $absence;
            $absence->addTableau($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): self
    {
        if ($this->absences->removeElement($absence)) {
            $absence->removeTableau($this);
        }

        return $this;
    }

    public function isPresence(): ?bool
    {
        return $this->presence;
    }

    public function setPresence(?bool $presence): self
    {
        $this->presence = $presence;

        return $this;
    }

    public function isAbsence(): ?bool
    {
        return $this->absence;
    }

    public function setAbsence(?bool $absence): self
    {
        $this->absence = $absence;

        return $this;
    }

    public function getDu(): ?\DateTimeInterface
    {
        return $this->du;
    }

    public function setDu(?\DateTimeInterface $du): self
    {
        $this->du = $du;

        return $this;
    }

    public function getAu(): ?\DateTimeInterface
    {
        return $this->au;
    }

    public function setAu(?\DateTimeInterface $au): self
    {
        $this->au = $au;

        return $this;
    }

    public function isEnretard(): ?bool
    {
        return $this->enretard;
    }

    public function setEnretard(?bool $enretard): self
    {
        $this->enretard = $enretard;

        return $this;
    }

    /**
     * @return Collection<int, Absintervenants>
     */
    public function getAbsintervenants(): Collection
    {
        return $this->absintervenants;
    }

    public function addAbsintervenant(Absintervenants $absintervenant): self
    {
        if (!$this->absintervenants->contains($absintervenant)) {
            $this->absintervenants[] = $absintervenant;
            $absintervenant->addTableau($this);
        }

        return $this;
    }

    public function removeAbsintervenant(Absintervenants $absintervenant): self
    {
        if ($this->absintervenants->removeElement($absintervenant)) {
            $absintervenant->removeTableau($this);
        }

        return $this;
    }

  
}
