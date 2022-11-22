<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TableauAbsencesintervenantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauAbsencesintervenantsRepository::class)
 */
#[ApiResource]
class TableauAbsencesintervenants
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Intervenants::class, inversedBy="tableauAbsencesintervenants")
     */
    private $intervenant;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateabsence;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $retard;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="tableauAbsencesintervenants")
     */
    private $copie;

    /**
     * @ORM\ManyToMany(targetEntity=Absintervenants::class, inversedBy="tableauAbsencesintervenants")
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

    public function __construct()
    {
        $this->intervenant = new ArrayCollection();
        $this->copie = new ArrayCollection();
        $this->absences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Intervenants>
     */
    public function getIntervenant(): Collection
    {
        return $this->intervenant;
    }

    public function addIntervenant(Intervenants $intervenant): self
    {
        if (!$this->intervenant->contains($intervenant)) {
            $this->intervenant[] = $intervenant;
        }

        return $this;
    }

    public function removeIntervenant(Intervenants $intervenant): self
    {
        $this->intervenant->removeElement($intervenant);

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
            $copie->setTableauAbsencesintervenants($this);
        }

        return $this;
    }

    public function removeCopie(Files $copie): self
    {
        if ($this->copie->removeElement($copie)) {
            // set the owning side to null (unless already changed)
            if ($copie->getTableauAbsencesintervenants() === $this) {
                $copie->setTableauAbsencesintervenants(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Absintervenants>
     */
    public function getAbsences(): Collection
    {
        return $this->absences;
    }

    public function addAbsence(Absintervenants $absence): self
    {
        if (!$this->absences->contains($absence)) {
            $this->absences[] = $absence;
        }

        return $this;
    }

    public function removeAbsence(Absintervenants $absence): self
    {
        $this->absences->removeElement($absence);

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

    public function setEnretard(bool $enretard): self
    {
        $this->enretard = $enretard;

        return $this;
    }
}
