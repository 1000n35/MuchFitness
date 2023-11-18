<?php

namespace App\Entity;

use App\Repository\ObjectifSeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjectifSeanceRepository::class)]
class ObjectifSeance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $jourObjectif = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourObjectif(): ?int
    {
        return $this->jourObjectif;
    }

    public function setJourObjectif(int $jourObjectif): static
    {
        $this->jourObjectif = $jourObjectif;

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
}
