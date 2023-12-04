<?php

namespace App\Entity;

use App\Repository\ProgrammeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgrammeRepository::class)]
class Programme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: Semaine::class)]
    private Collection $semaines;

    #[ORM\ManyToOne(inversedBy: 'programmes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createur = null;

    #[ORM\OneToMany(mappedBy: 'programme', targetEntity: SeanceType::class)]
    private Collection $seanceTypes;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'progFavoris')]
    private Collection $estFavori;

    public function __construct()
    {
        $this->semaines = new ArrayCollection();
        $this->seanceTypes = new ArrayCollection();
        $this->estFavori = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

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
            $semaine->setProgramme($this);
        }

        return $this;
    }

    public function removeSemaine(Semaine $semaine): static
    {
        if ($this->semaines->removeElement($semaine)) {
            // set the owning side to null (unless already changed)
            if ($semaine->getProgramme() === $this) {
                $semaine->setProgramme(null);
            }
        }

        return $this;
    }

    public function getCreateur(): ?User
    {
        return $this->createur;
    }

    public function setCreateur(?User $createur): static
    {
        $this->createur = $createur;

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
            $seanceType->setProgramme($this);
        }

        return $this;
    }

    public function removeSeanceType(SeanceType $seanceType): static
    {
        if ($this->seanceTypes->removeElement($seanceType)) {
            // set the owning side to null (unless already changed)
            if ($seanceType->getProgramme() === $this) {
                $seanceType->setProgramme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getEstFavori(): Collection
    {
        return $this->estFavori;
    }

    public function addEstFavori(User $estFavori): static
    {
        if (!$this->estFavori->contains($estFavori)) {
            $this->estFavori->add($estFavori);
        }

        return $this;
    }

    public function removeEstFavori(User $estFavori): static
    {
        $this->estFavori->removeElement($estFavori);

        return $this;
    }
}
