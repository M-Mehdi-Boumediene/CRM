<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AbsencesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=AbsencesRepository::class)
 */
class Absences
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=Etudiants::class, inversedBy="absences")
     */
    private $etudiant;

    /**
     * @ORM\ManyToMany(targetEntity=Intervenants::class, inversedBy="absences")
     */
    private $intervenant;

    /**
     * @ORM\ManyToOne(targetEntity=Modules::class, inversedBy="absences")
     */
    private $module;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $created_by;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $du;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $au;

  

    /**
     * @ORM\ManyToOne(targetEntity=Classes::class, inversedBy="absences")
     */
    private $classe;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $absent;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateabsence;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $enretard;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateretard;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $present;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datepresence;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $userid;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="absences")
     */
    private $user;

 



  



    public function __construct()
    {
        $this->etudiant = new ArrayCollection();
        $this->intervenant = new ArrayCollection();

    
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;

        return $this;
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

    public function getModule(): ?Modules
    {
        return $this->module;
    }

    public function setModule(?Modules $module): self
    {
        $this->module = $module;

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

    

    public function getClasse(): ?Classes
    {
        return $this->classe;
    }

    public function setClasse(?Classes $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    public function isAbsent(): ?bool
    {
        return $this->absent;
    }

    public function setAbsent(?bool $absent): self
    {
        $this->absent = $absent;

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

    public function isEnretard(): ?bool
    {
        return $this->enretard;
    }

    public function setEnretard(?bool $enretard): self
    {
        $this->enretard = $enretard;

        return $this;
    }

    public function getDateretard(): ?\DateTimeInterface
    {
        return $this->dateretard;
    }

    public function setDateretard(?\DateTimeInterface $dateretard): self
    {
        $this->dateretard = $dateretard;

        return $this;
    }

    public function isPresent(): ?bool
    {
        return $this->present;
    }

    public function setPresent(?bool $present): self
    {
        $this->present = $present;

        return $this;
    }

    public function getDatepresence(): ?\DateTimeInterface
    {
        return $this->datepresence;
    }

    public function setDatepresence(?\DateTimeInterface $datepresence): self
    {
        $this->datepresence = $datepresence;

        return $this;
    }

    public function getUserid(): ?string
    {
        return $this->userid;
    }

    public function setUserid(?string $userid): self
    {
        $this->userid = $userid;

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

    

   

}
