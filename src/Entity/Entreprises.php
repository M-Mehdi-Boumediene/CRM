<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EntreprisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=EntreprisesRepository::class)
 */
class Entreprises
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $responsable;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity=Tuteurs::class, mappedBy="entreprise")
     */
    private $tuteurs;

    /**
     * @ORM\OneToMany(targetEntity=Etudiants::class, mappedBy="entreprise")
     */
    private $etudiants;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailrepresentant1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emailrepresentant2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numerotelephone1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numeortelephone2;



    public function __construct()
    {
        $this->tuteurs = new ArrayCollection();
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

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(?string $responsable): self
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCreatedBy(): ?string
    {
        return $this->created_by;
    }

    public function setCreatedBy(?string $created_by): self
    {
        $this->created_by = $created_by;

        return $this;
    }

    /**
     * @return Collection<int, Tuteurs>
     */
    public function getTuteurs(): Collection
    {
        return $this->tuteurs;
    }

    public function addTuteur(Tuteurs $tuteur): self
    {
        if (!$this->tuteurs->contains($tuteur)) {
            $this->tuteurs[] = $tuteur;
            $tuteur->setEntreprise($this);
        }

        return $this;
    }

    public function removeTuteur(Tuteurs $tuteur): self
    {
        if ($this->tuteurs->removeElement($tuteur)) {
            // set the owning side to null (unless already changed)
            if ($tuteur->getEntreprise() === $this) {
                $tuteur->setEntreprise(null);
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
            $etudiant->setEntreprise($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getEntreprise() === $this) {
                $etudiant->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getEmailrepresentant1(): ?string
    {
        return $this->emailrepresentant1;
    }

    public function setEmailrepresentant1(?string $emailrepresentant1): self
    {
        $this->emailrepresentant1 = $emailrepresentant1;

        return $this;
    }

    public function getEmailrepresentant2(): ?string
    {
        return $this->emailrepresentant2;
    }

    public function setEmailrepresentant2(?string $emailrepresentant2): self
    {
        $this->emailrepresentant2 = $emailrepresentant2;

        return $this;
    }

    public function getNumerotelephone1(): ?string
    {
        return $this->numerotelephone1;
    }

    public function setNumerotelephone1(?string $numerotelephone1): self
    {
        $this->numerotelephone1 = $numerotelephone1;

        return $this;
    }

    public function getNumeortelephone2(): ?string
    {
        return $this->numeortelephone2;
    }

    public function setNumeortelephone2(?string $numeortelephone2): self
    {
        $this->numeortelephone2 = $numeortelephone2;

        return $this;
    }

  
}
