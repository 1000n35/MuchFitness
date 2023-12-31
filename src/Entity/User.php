<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Serializable; // Importez l'interface Serializable

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]

class User implements UserInterface, PasswordAuthenticatedUserInterface,Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]


    // ID
    #[ORM\Column]
    private ?int $id = null;

    // USERNAME
    #[Assert\NotBlank()]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    // ROLES
    #[ORM\Column]
    private array $roles = [];

    // MDP
    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank()]
    #[ORM\Column]
    private ?string $password = null;

    // EMAIL
    #[Assert\Email()]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    // NOM
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    // PRENOM
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    // DATE DE NAISSANCE
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    // TELEPHONE
    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    // IS COACH
    #[ORM\Column]
    private ?bool $coach = null;

    // SEMAINES APPARTIENNENT À USER
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Semaine::class)]
    private Collection $semaines;

    // PROGRAMMES CRÉÉS PAR USER
    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: Programme::class)]
    private Collection $programmes;

    // SEANCES TYPE CRÉÉS PAR USER
    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: SeanceType::class)]
    private Collection $seanceTypes;

    // EXERCICES CRÉÉS PAR USER
    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: Exercice::class)]
    private Collection $exercices;

    // FAVORIS DE USER
    #[ORM\ManyToMany(targetEntity: Programme::class, mappedBy: 'estFavori')]
    private Collection $progFavoris;

    // PROGRAMME SUIVI PAR USER
    #[ORM\ManyToOne(inversedBy: 'pratiquants')]
    private ?Programme $progSuivi = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[Vich\UploadableField(mapping: 'user_profile_picture', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?string $imagePath = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;


    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
        $this->semaines = new ArrayCollection();
        $this->programmes = new ArrayCollection();
        $this->seanceTypes = new ArrayCollection();
        $this->exercices = new ArrayCollection();
        $this->progFavoris = new ArrayCollection();
        $this->coach = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isCoach(): ?bool
    {
        return $this->coach;
    }

    public function setCoach(bool $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    /**
     * @return Collection<int, Semaine>
     */
    public function getSemaines(): Collection
    {
        return $this->semaines;
    }

    public function addSemaine(Semaine $semaine): static
    {
        if (!$this->semaines->contains($semaine)) {
            $this->semaines->add($semaine);
            $semaine->setUser($this);
        }

        return $this;
    }

    public function removeSemaine(Semaine $semaine): static
    {
        if ($this->semaines->removeElement($semaine)) {
            // set the owning side to null (unless already changed)
            if ($semaine->getUser() === $this) {
                $semaine->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setCreateur($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getCreateur() === $this) {
                $programme->setCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SeanceType>
     */
    public function getSeanceTypes(): Collection
    {
        return $this->seanceTypes;
    }

    public function addSeanceType(SeanceType $seanceType): static
    {
        if (!$this->seanceTypes->contains($seanceType)) {
            $this->seanceTypes->add($seanceType);
            $seanceType->setCreateur($this);
        }

        return $this;
    }

    public function removeSeanceType(SeanceType $seanceType): static
    {
        if ($this->seanceTypes->removeElement($seanceType)) {
            // set the owning side to null (unless already changed)
            if ($seanceType->getCreateur() === $this) {
                $seanceType->setCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercices(): Collection
    {
        return $this->exercices;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercices->contains($exercice)) {
            $this->exercices->add($exercice);
            $exercice->setCreateur($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercices->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getCreateur() === $this) {
                $exercice->setCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgFavoris(): Collection
    {
        return $this->progFavoris;
    }

    public function addProgFavori(Programme $progFavori): static
    {
        if (!$this->progFavoris->contains($progFavori)) {
            $this->progFavoris->add($progFavori);
            $progFavori->addEstFavori($this);
        }

        return $this;
    }

    public function removeProgFavori(Programme $progFavori): static
    {
        if ($this->progFavoris->removeElement($progFavori)) {
            $progFavori->removeEstFavori($this);
        }
        return $this;
    }

    public function getProgSuivi(): ?Programme
    {
        return $this->progSuivi;
    }

    public function setProgSuivi(?Programme $progSuivi): static
    {
        $this->progSuivi = $progSuivi;

        return $this;
    }

    public function __toString()
    {
        return $this->id.' '.$this->username.' '.$this->email;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->roles,
            $this->password,
            $this->email,
            // ... autres propriétés que vous souhaitez sérialiser ...
        ]);
    }

    public function unserialize($serialized): void
    {
        [
            $this->id,
            $this->username,
            $this->roles,
            $this->password,
            $this->email,
            // ... autres propriétés que vous souhaitez désérialiser ...
        ] = unserialize($serialized);
    }
    
}


