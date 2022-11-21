<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ApiResource()
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true,nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $created_by;


  

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="sender", orphanRemoval=true)
     */
    private $sent;


 

    /**
     * @ORM\OneToMany(targetEntity=Blocs::class, mappedBy="user")
     */
    private $blocs;



    /**
     * @ORM\OneToMany(targetEntity=Intervenants::class, mappedBy="user")
     */
    private $intervenants;

    /**
     * @ORM\OneToMany(targetEntity=Etudiants::class, mappedBy="user")
     */
    private $etudiants;


    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="users")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Users::class, mappedBy="user")
     */
    private $users;

   

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="user")
     */
    private $documents;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\OneToMany(targetEntity=Modules::class, mappedBy="users")
     */
    private $module;

 

    /**
     * @ORM\OneToMany(targetEntity=Tuteurs::class, mappedBy="users")
     */
    private $tuteur;



    /**
     * @ORM\OneToMany(targetEntity=Calendrier::class, mappedBy="intervenant")
     */
    private $calendriers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $etat = "En attente";

    /**
     * @ORM\OneToMany(targetEntity=Absences::class, mappedBy="user")
     */
    private $absences;


  /**
     * @ORM\OneToMany(targetEntity=Telechargements::class, mappedBy="user")
     */
    private $telechargements;

    /**
     * @ORM\ManyToOne(targetEntity=Classes::class, inversedBy="users")
     */
    private $classe;


    /**
     * @ORM\ManyToMany(targetEntity=Messages::class, inversedBy="users")
     */
    private $received;

    /**
     * @ORM\OneToMany(targetEntity=Signatures::class, mappedBy="user")
     */
    private $signatures;

    /**
     * @ORM\OneToMany(targetEntity=Entreprises::class, mappedBy="users")
     */
    private $entreprises;

    /**
     * @ORM\ManyToMany(targetEntity=Docadmins::class, mappedBy="users")
     */
    private $docadmins;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=Cv::class, mappedBy="user")
     */
    private $cvs;

    /**
     * @ORM\OneToMany(targetEntity=Absintervenants::class, mappedBy="user")
     */
    private $absintervenants;

 

   

   


    public function __construct()
    {
        $this->sent = new ArrayCollection();
      
        $this->blocs = new ArrayCollection();
        $this->intervenants = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->module = new ArrayCollection();
        $this->tuteur = new ArrayCollection();
    
        $this->calendriers = new ArrayCollection();
        $this->absences = new ArrayCollection();
        $this->telechargements = new ArrayCollection();

        $this->received = new ArrayCollection();
        $this->signatures = new ArrayCollection();
        $this->entreprises = new ArrayCollection();
        $this->docadmins = new ArrayCollection();
        $this->cvs = new ArrayCollection();
        $this->absintervenants = new ArrayCollection();
      
 
     
       
      
    }


    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ADMIN';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

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
     * @return Collection<int, Messages>
     */
    public function getSent(): Collection
    {
        return $this->sent;
    }

    public function addSent(Messages $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    public function removeSent(Messages $sent): self
    {
        if ($this->sent->removeElement($sent)) {
            // set the owning side to null (unless already changed)
            if ($sent->getSender() === $this) {
                $sent->setSender(null);
            }
        }

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
            $bloc->setUser($this);
        }

        return $this;
    }

    public function removeBloc(Blocs $bloc): self
    {
        if ($this->blocs->removeElement($bloc)) {
            // set the owning side to null (unless already changed)
            if ($bloc->getUser() === $this) {
                $bloc->setUser(null);
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
            $intervenant->setUser($this);
        }

        return $this;
    }

    public function removeIntervenant(Intervenants $intervenant): self
    {
        if ($this->intervenants->removeElement($intervenant)) {
            // set the owning side to null (unless already changed)
            if ($intervenant->getUser() === $this) {
                $intervenant->setUser(null);
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
            $etudiant->setUser($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiants $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getUser() === $this) {
                $etudiant->setUser(null);
            }
        }

        return $this;
    }

    public function getUser(): ?self
    {
        return $this->user;
    }

    public function setUser(?self $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setUser($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUser() === $this) {
                $user->setUser(null);
            }
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
            $document->setUser($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getUser() === $this) {
                $document->setUser(null);
            }
        }

        return $this;
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

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Modules>
     */
    public function getModule(): Collection
    {
        return $this->module;
    }

    public function addModule(Modules $module): self
    {
        if (!$this->module->contains($module)) {
            $this->module[] = $module;
            $module->setUsers($this);
        }

        return $this;
    }

    public function removeModule(Modules $module): self
    {
        if ($this->module->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getUsers() === $this) {
                $module->setUsers(null);
            }
        }

        return $this;
    }


    

    /**
     * @return Collection<int, Tuteurs>
     */
    public function getTuteur(): Collection
    {
        return $this->tuteur;
    }

    public function addTuteur(Tuteurs $tuteur): self
    {
        if (!$this->tuteur->contains($tuteur)) {
            $this->tuteur[] = $tuteur;
            $tuteur->setUsers($this);
        }

        return $this;
    }

    public function removeTuteur(Tuteurs $tuteur): self
    {
        if ($this->tuteur->removeElement($tuteur)) {
            // set the owning side to null (unless already changed)
            if ($tuteur->getUsers() === $this) {
                $tuteur->setUsers(null);
            }
        }

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

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): self
    {
        $this->etat = $etat;

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
            $absence->setUser($this);
        }

        return $this;
    }

    public function removeAbsence(Absences $absence): self
    {
        if ($this->absences->removeElement($absence)) {
            // set the owning side to null (unless already changed)
            if ($absence->getUser() === $this) {
                $absence->setUser(null);
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
            $telechargement->setUser($this);
        }

        return $this;
    }

    public function removeTelechargement(Telechargements $telechargement): self
    {
        if ($this->telechargements->removeElement($telechargement)) {
            // set the owning side to null (unless already changed)
            if ($telechargement->getUser() === $this) {
                $telechargement->setUser(null);
            }
        }

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



    /**
     * @return Collection<int, Messages>
     */
    public function getReceived(): Collection
    {
        return $this->received;
    }

    public function addReceived(Messages $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
        }

        return $this;
    }

    public function removeReceived(Messages $received): self
    {
        $this->received->removeElement($received);

        return $this;
    }

    /**
     * @return Collection<int, Signatures>
     */
    public function getSignatures(): Collection
    {
        return $this->signatures;
    }

    public function addSignature(Signatures $signature): self
    {
        if (!$this->signatures->contains($signature)) {
            $this->signatures[] = $signature;
            $signature->setUser($this);
        }

        return $this;
    }

    public function removeSignature(Signatures $signature): self
    {
        if ($this->signatures->removeElement($signature)) {
            // set the owning side to null (unless already changed)
            if ($signature->getUser() === $this) {
                $signature->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entreprises>
     */
    public function getEntreprises(): Collection
    {
        return $this->entreprises;
    }

    public function addEntreprise(Entreprises $entreprise): self
    {
        if (!$this->entreprises->contains($entreprise)) {
            $this->entreprises[] = $entreprise;
            $entreprise->setUsers($this);
        }

        return $this;
    }

    public function removeEntreprise(Entreprises $entreprise): self
    {
        if ($this->entreprises->removeElement($entreprise)) {
            // set the owning side to null (unless already changed)
            if ($entreprise->getUsers() === $this) {
                $entreprise->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Docadmins>
     */
    public function getDocadmins(): Collection
    {
        return $this->docadmins;
    }

    public function addDocadmin(Docadmins $docadmin): self
    {
        if (!$this->docadmins->contains($docadmin)) {
            $this->docadmins[] = $docadmin;
            $docadmin->addUser($this);
        }

        return $this;
    }

    public function removeDocadmin(Docadmins $docadmin): self
    {
        if ($this->docadmins->removeElement($docadmin)) {
            $docadmin->removeUser($this);
        }

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    /**
     * @return Collection<int, Cv>
     */
    public function getCvs(): Collection
    {
        return $this->cvs;
    }

    public function addCv(Cv $cv): self
    {
        if (!$this->cvs->contains($cv)) {
            $this->cvs[] = $cv;
            $cv->setUser($this);
        }

        return $this;
    }

    public function removeCv(Cv $cv): self
    {
        if ($this->cvs->removeElement($cv)) {
            // set the owning side to null (unless already changed)
            if ($cv->getUser() === $this) {
                $cv->setUser(null);
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
            $absintervenant->setUser($this);
        }

        return $this;
    }

    public function removeAbsintervenant(Absintervenants $absintervenant): self
    {
        if ($this->absintervenants->removeElement($absintervenant)) {
            // set the owning side to null (unless already changed)
            if ($absintervenant->getUser() === $this) {
                $absintervenant->setUser(null);
            }
        }

        return $this;
    }

   

   
}