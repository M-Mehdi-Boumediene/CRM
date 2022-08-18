<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClassesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=ClassesRepository::class)
 */
class Classes
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
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $created_by;

    /**
     * @ORM\OneToMany(targetEntity=Blocs::class, mappedBy="Classe")
     */
    private $blocs;


    /**
     * @ORM\OneToMany(targetEntity=Etudiants::class, mappedBy="classes")
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Intervenants::class, mappedBy="classes")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity=Modules::class, mappedBy="classes")
     */
    private $modules;

 

    /**
     * @ORM\OneToMany(targetEntity=Calendrier::class, mappedBy="classe")
     */
    private $calendrier;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class, mappedBy="classe")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Absences::class, mappedBy="classe")
     */
    private $absences;

     /**
     * @ORM\OneToMany(targetEntity=Telechargements::class, mappedBy="classe")
     */
    private $telechargements;


    public function __toString() {
        return $this->nom;
    }

    public function __construct()
    {
        $this->blocs = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->intervenants = new ArrayCollection();
        $this->modules = new ArrayCollection();
        $this->calendriers = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->telechargements = new ArrayCollection();
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
     * @return Collection<int, Blocs>
     */
    public function getBlocs(): Collection
    {
        return $this->blocs;
    }

    public function addBloc(Blocs $bloc): self
    {
        if (!$this->blocs->contains($bloc)) {
            $this->blocs[] = $bloc;
            $bloc->setClasse($this);
        }

        return $this;
    }

    public function removeBloc(Blocs $bloc): self
    {
        if ($this->blocs->removeElement($bloc)) {
            // set the owning side to null (unless already changed)
            if ($bloc->getClasse() === $this) {
                $bloc->setClasse(null);
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
            $etudiant->setClasses($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getClasses() === $this) {
                $etudiant->setClasses(null);
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
            $intervenant->setClasses($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenants $intervenant): self
    {
        if ($this->intervenants->removeElement($intervenant)) {
            // set the owning side to null (unless already changed)
            if ($intervenant->getClasses() === $this) {
                $intervenant->setClasses(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Modules>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Modules $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
            $module->setClasses($this);
        }

        return $this;
    }

    public function removeModule(Modules $module): self
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getClasses() === $this) {
                $module->setClasses(null);
            }
        }

        return $this;
    }

   

    /**
     * @return Collection<int, Calendrier>
     */
    public function getCalendrier(): Collection
    {
        return $this->calendrier;
    }

    public function addCalendrier(Calendrier $calendrier): self
    {
        if (!$this->calendrier->contains($calendrier)) {
            $this->calendrier[] = $calendrier;
            $calendrier->setClasse($this);
        }

        return $this;
    }

    public function removeCalendrier(Calendrier $calendrier): self
    {
        if ($this->calendrier->removeElement($calendrier)) {
            // set the owning side to null (unless already changed)
            if ($calendrier->getClasse() === $this) {
                $calendrier->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addClasse($this);
        }

        return $this;
    }

    public function removeUser(Users $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeClasse($this);
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
            $absence->setClasse($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): self
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getClasse() === $this) {
                $absence->setClasse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Telechargements>
     */
    public function getTelechargements(): Collection
    {
        return $this->telechargements;
    }

    public function addTelechargement(Telechargements $telechargement): self
    {
        if (!$this->telechargements->contains($telechargement)) {
            $this->telechargements[] = $telechargement;
            $telechargement->setClasse($this);
        }

        return $this;
    }

    public function removeTelechargement(Telechargements $telechargement): self
    {
        if ($this->telechargements->removeElement($telechargement)) {
            // set the owning side to null (unless already changed)
            if ($telechargement->getClasse() === $this) {
                $telechargement->setClasse(null);
            }
        }

        return $this;
    }

   
}
