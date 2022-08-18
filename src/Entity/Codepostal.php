<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CodepostalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=CodepostalRepository::class)
 */
class Codepostal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Villes::class, inversedBy="codepostale")
     */
    private $villes;



    /**
     * @ORM\OneToMany(targetEntity=Intervenants::class, mappedBy="codepostale")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity=Etudiants::class, mappedBy="codepostale")
     */
    private $etudiants;

    public function __construct()
    {
    
        $this->intervenants = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVilles(): ?Villes
    {
        return $this->villes;
    }

    public function setVilles(?Villes $villes): self
    {
        $this->villes = $villes;

        return $this;
    }

   

    

    /**
     * @return Collection<int, Intervenants>
     */
    public function getIntervenants(): Collection
    {
        return $this->intervenants;
    }

    public function addIntervenant(Intervenants $intervenant): self
    {
        if (!$this->intervenants->contains($intervenant)) {
            $this->intervenants[] = $intervenant;
            $intervenant->setCodepostale($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenants $intervenant): self
    {
        if ($this->intervenants->removeElement($intervenant)) {
            // set the owning side to null (unless already changed)
            if ($intervenant->getCodepostale() === $this) {
                $intervenant->setCodepostale(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Etudiants>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiants $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setCodepostale($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getCodepostale() === $this) {
                $etudiant->setCodepostale(null);
            }
        }

        return $this;
    }
}
