<?php

namespace App\Entity;

use App\Repository\SuiviSeanceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuiviSeanceRepository::class)]
class SuiviSeance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $jourSeance = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptif = null;

    #[ORM\ManyToOne(inversedBy: 'suiviSeances')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Semaine $semaine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJourSeance(): ?int
    {
        return $this->jourSeance;
    }

    public function setJourSeance(int $jourSeance): static
    {
        $this->jourSeance = $jourSeance;

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

    public function getSemaine(): ?Semaine
    {
        return $this->semaine;
    }

    public function setSemaine(?Semaine $semaine): static
    {
        $this->semaine = $semaine;

        return $this;
    }
}
