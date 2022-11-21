<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\IntervenantsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=IntervenantsRepository::class)
 */
class Intervenants
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
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity=Modules::class, inversedBy="intervenants",cascade={"persist"})
     */
    private $modules;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $created_by;

    /**
     * @ORM\ManyToMany(targetEntity=Absences::class, mappedBy="intervenant")
     */
    private $absences;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="intervenant")
     */
    private $documents;

    /**
     * @ORM\ManyToOne(targetEntity=Classes::class, inversedBy="intervenants")
     */
    private $classes;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="intervenants",cascade={"all"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Codepostal::class, inversedBy="intervenants")
     */
    private $codepostale;

    /**
     * @ORM\ManyToOne(targetEntity=Villes::class, inversedBy="intervenants")
     */
    private $ville;

    /**
     * @ORM\OneToMany(targetEntity=Calendrier::class, mappedBy="intervenant")
     */
    private $calendriers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cat;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $istuteur;

    /**
     * @ORM\OneToMany(targetEntity=Etudiants::class, mappedBy="intervenants")
     */
    private $apprenants;

    /**
     * @ORM\ManyToMany(targetEntity=Absintervenants::class, mappedBy="intervenant")
     */
    private $absintervenants;

   

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->calendriers = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
        $this->absintervenants = new ArrayCollection();

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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
        }

        return $this;
    }

    public function removeModule(Modules $module): self
    {
        $this->modules->removeElement($module);

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
            $absence->addIntervenant($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): self
    {
        if ($this->absences->removeElement($absence)) {
            $absence->removeIntervenant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Documents>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setIntervenant($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getIntervenant() === $this) {
                $document->setIntervenant(null);
            }
        }

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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCodepostale(): ?Codepostal
    {
        return $this->codepostale;
    }

    public function setCodepostale(?Codepostal $codepostale): self
    {
        $this->codepostale = $codepostale;

        return $this;
    }

    public function getVille(): ?Villes
    {
        return $this->ville;
    }

    public function setVille(?Villes $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * @return Collection<int, Calendrier>
     */
    public function getCalendriers(): Collection
    {
        return $this->calendriers;
    }

    public function addCalendrier(Calendrier $calendrier): self
    {
        if (!$this->calendriers->contains($calendrier)) {
            $this->calendriers[] = $calendrier;
            $calendrier->setIntervenant($this);
        }

        return $this;
    }

    public function removeCalendrier(Calendrier $calendrier): self
    {
        if ($this->calendriers->removeElement($calendrier)) {
            // set the owning side to null (unless already changed)
            if ($calendrier->getIntervenant() === $this) {
                $calendrier->setIntervenant(null);
            }
        }

        return $this;
    }

    public function getCat(): ?string
    {
        return $this->cat;
    }

    public function setCat(?string $cat): self
    {
        $this->cat = $cat;

        return $this;
    }

    public function isIstuteur(): ?bool
    {
        return $this->istuteur;
    }

    public function setIstuteur(?bool $istuteur): self
    {
        $this->istuteur = $istuteur;

        return $this;
    }

    /**
     * @return Collection<int, Etudiants>
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Etudiants $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->setIntervenants($this);
        }

        return $this;
    }

    public function removeApprenant(Etudiants $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            // set the owning side to null (unless already changed)
            if ($apprenant->getIntervenants() === $this) {
                $apprenant->setIntervenants(null);
            }
        }

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
            $absintervenant->addIntervenant($this);
        }

        return $this;
    }

    public function removeAbsintervenant(Absintervenants $absintervenant): self
    {
        if ($this->absintervenants->removeElement($absintervenant)) {
            $absintervenant->removeIntervenant($this);
        }

        return $this;
    }

   
}
