<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomExercice = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptif = null;

    #[ORM\Column(type: 'string')]
    private string $videoFilename;

    #[ORM\ManyToOne(inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createur = null;

    #[ORM\ManyToMany(targetEntity: SeanceType::class, inversedBy: 'exercices')]
    private Collection $contient;

    public function __construct()
    {
        $this->contient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomExercice(): ?string
    {
        return $this->nomExercice;
    }

    public function setNomExercice(string $nomExercice): static
    {
        $this->nomExercice = $nomExercice;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): static
    {
        $this->descriptif = $descriptif;

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
    public function getContient(): Collection
    {
        return $this->contient;
    }

    public function addContient(SeanceType $contient): static
    {
        if (!$this->contient->contains($contient)) {
            $this->contient->add($contient);
        }

        return $this;
    }

    public function removeContient(SeanceType $contient): static
    {
        $this->contient->removeElement($contient);

        return $this;
    }

    public function getVideoFilename(): string
    {
        return $this->videoFilename;
    }

    public function setVideoFilename(string $videoFilename): self
    {
        $this->videoFilename = $videoFilename;

        return $this;
    }
}
