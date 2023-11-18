<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column(length: 255)]
    private ?string $video = null;

    #[ORM\ManyToOne(inversedBy: 'exercices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $createur = null;

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

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): static
    {
        $this->video = $video;

        return $this;
    }

    public function getCreateur(): ?Utilisateur
    {
        return $this->createur;
    }

    public function setCreateur(?Utilisateur $createur): static
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
}
