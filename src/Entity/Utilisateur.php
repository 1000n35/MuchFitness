<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?float $poids = null;

    #[ORM\Column]
    private ?int $taille = null;

    #[ORM\Column(length: 255)]
    private ?string $metabolisme = null;

    #[ORM\Column]
    private ?bool $isCoach = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Semaine::class)]
    private Collection $semaines;

    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: Programme::class)]
    private Collection $programmes;

    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: SeanceType::class)]
    private Collection $seanceTypes;

    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: Exercice::class)]
    private Collection $exercices;

    #[ORM\ManyToMany(targetEntity: Programme::class, mappedBy: 'estFavori')]
    private Collection $progFavoris;

    public function __construct()
    {
        $this->semaines = new ArrayCollection();
        $this->programmes = new ArrayCollection();
        $this->seanceTypes = new ArrayCollection();
        $this->exercices = new ArrayCollection();
        $this->progFavoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

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

    public function getPoids(): ?float
    {
        return $this->poids;
    }

    public function setPoids(float $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getMetabolisme(): ?string
    {
        return $this->metabolisme;
    }

    public function setMetabolisme(string $metabolisme): static
    {
        $this->metabolisme = $metabolisme;

        return $this;
    }

    public function isIsCoach(): ?bool
    {
        return $this->isCoach;
    }

    public function setIsCoach(bool $isCoach): static
    {
        $this->isCoach = $isCoach;

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
            $semaine->setUtilisateur($this);
        }

        return $this;
    }

    public function removeSemaine(Semaine $semaine): static
    {
        if ($this->semaines->removeElement($semaine)) {
            // set the owning side to null (unless already changed)
            if ($semaine->getUtilisateur() === $this) {
                $semaine->setUtilisateur(null);
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
}
