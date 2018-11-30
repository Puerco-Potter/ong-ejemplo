<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

// DON'T forget this use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ColaboratorRepository")
 * @UniqueEntity("email")
 */
class Colaborator
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $cuit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="colaboratorsCuils")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ong;

    public function __toString()
    {
        return (string) $this->cuit;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(?string $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getOng(): ?Ong
    {
        return $this->ong;
    }

    public function setOng(?Ong $ong): self
    {
        $this->ong = $ong;

        return $this;
    }
}
