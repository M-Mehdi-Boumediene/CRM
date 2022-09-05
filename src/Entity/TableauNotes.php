<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TableauNotesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableauNotesRepository::class)
 */
#[ApiResource]
class TableauNotes
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
    private $note1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $note3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $observation3;

    /**
     * @ORM\OneToOne(targetEntity=Etudiants::class, inversedBy="tableauNotes", cascade={"persist", "remove"})
     */
    private $etudiant;

    /**
     * @ORM\ManyToMany(targetEntity=Notes::class, mappedBy="tableau")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity=Files::class, mappedBy="tableauNotes", cascade={"all"})
     */
    private $copie;



    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->copie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote1(): ?string
    {
        return $this->note1;
    }

    public function setNote1(?string $note1): self
    {
        $this->note1 = $note1;

        return $this;
    }

    public function getNote2(): ?string
    {
        return $this->note2;
    }

    public function setNote2(?string $note2): self
    {
        $this->note2 = $note2;

        return $this;
    }

    public function getNote3(): ?string
    {
        return $this->note3;
    }

    public function setNote3(?string $note3): self
    {
        $this->note3 = $note3;

        return $this;
    }

    public function getObservation1(): ?string
    {
        return $this->observation1;
    }

    public function setObservation1(?string $observation1): self
    {
        $this->observation1 = $observation1;

        return $this;
    }

    public function getObservation2(): ?string
    {
        return $this->observation2;
    }

    public function setObservation2(?string $observation2): self
    {
        $this->observation2 = $observation2;

        return $this;
    }

    public function getObservation3(): ?string
    {
        return $this->observation3;
    }

    public function setObservation3(?string $observation3): self
    {
        $this->observation3 = $observation3;

        return $this;
    }

   

    public function getEtudiant(): ?Etudiants
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiants $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->addTableau($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->notes->removeElement($note)) {
            $note->removeTableau($this);
        }

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
            $copie->setTableauNotes($this);
        }

        return $this;
    }

    public function removeCopie(Files $copie): self
    {
        if ($this->copie->removeElement($copie)) {
            // set the owning side to null (unless already changed)
            if ($copie->getTableauNotes() === $this) {
                $copie->setTableauNotes(null);
            }
        }

        return $this;
    }
}
