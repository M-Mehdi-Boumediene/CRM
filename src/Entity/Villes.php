<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\VillesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=VillesRepository::class)
 */
class Villes
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
     * @ORM\OneToMany(targetEntity=Codepostal::class, mappedBy="villes",cascade={"persist"})
     */
    private $codepostale;

    /**
     * @ORM\OneToMany(targetEntity=Intervenants::class, mappedBy="ville")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity=Etudiants::class, mappedBy="ville")
     */
    private $etudiants;

    public function __construct()
    {
        $this->codepostale = new ArrayCollection();
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

    /**
     * @return Collection<int, Codepostal>
     */
    public function getCodepostale(): Collection
    {
        return $this->codepostale;
    }

    public function addCodepostale(Codepostal $codepostale): self
    {
        if (!$this->codepostale->contains($codepostale)) {
            $this->codepostale[] = $codepostale;
            $codepostale->setVilles($this);
        }

        return $this;
    }

    public function removeCodepostale(Codepostal $codepostale): self
    {
        if ($this->codepostale->removeElement($codepostale)) {
            // set the owning side to null (unless already changed)
            if ($codepostale->getVilles() === $this) {
                $codepostale->setVilles(null);
            }
        }

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
            $intervenant->setVille($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenants $intervenant): self
    {
        if ($this->intervenants->removeElement($intervenant)) {
            // set the owning side to null (unless already changed)
            if ($intervenant->getVille() === $this) {
                $intervenant->setVille(null);
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
            $etudiant->setVille($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getVille() === $this) {
                $etudiant->setVille(null);
            }
        }

        return $this;
    }
}
