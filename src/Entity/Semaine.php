<?php

namespace App\Entity;

use App\Repository\SemaineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SemaineRepository::class)]
class Semaine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\OneToMany(mappedBy: 'semaine', targetEntity: SuiviSeance::class)]
    private Collection $suiviSeances;

    #[ORM\OneToMany(mappedBy: 'semaine', targetEntity: ObjectifSeance::class)]
    private Collection $objectifSeances;

    #[ORM\ManyToOne(inversedBy: 'semaines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Programme $programme = null;

    #[ORM\ManyToOne(inversedBy: 'semaines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    public function __construct()
    {
        $this->suiviSeances = new ArrayCollection();
        $this->objectifSeances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * @return Collection<int, SuiviSeance>
     */
    public function getSuiviSeances(): Collection
    {
        return $this->suiviSeances;
    }

    public function addSuiviSeance(SuiviSeance $suiviSeance): static
    {
        if (!$this->suiviSeances->contains($suiviSeance)) {
            $this->suiviSeances->add($suiviSeance);
            $suiviSeance->setSemaine($this);
        }

        return $this;
    }

    public function removeSuiviSeance(SuiviSeance $suiviSeance): static
    {
        if ($this->suiviSeances->removeElement($suiviSeance)) {
            // set the owning side to null (unless already changed)
            if ($suiviSeance->getSemaine() === $this) {
                $suiviSeance->setSemaine(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ObjectifSeance>
     */
    public function getObjectifSeances(): Collection
    {
        return $this->objectifSeances;
    }

    public function addObjectifSeance(ObjectifSeance $objectifSeance): static
    {
        if (!$this->objectifSeances->contains($objectifSeance)) {
            $this->objectifSeances->add($objectifSeance);
            $objectifSeance->setSemaine($this);
        }

        return $this;
    }

    public function removeObjectifSeance(ObjectifSeance $objectifSeance): static
    {
        if ($this->objectifSeances->removeElement($objectifSeance)) {
            // set the owning side to null (unless already changed)
            if ($objectifSeance->getSemaine() === $this) {
                $objectifSeance->setSemaine(null);
            }
        }

        return $this;
    }

    public function getProgramme(): ?Programme
    {
        return $this->programme;
    }

    public function setProgramme(?Programme $programme): static
    {
        $this->programme = $programme;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
}
