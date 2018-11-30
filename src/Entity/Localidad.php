<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocalidadRepository")
 */
class Localidad
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nombre;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ValorAsignado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): self
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getValorAsignado(): ?int
    {
        return $this->ValorAsignado;
    }

    public function setValorAsignado(?int $ValorAsignado): self
    {
        $this->ValorAsignado = $ValorAsignado;

        return $this;
    }
}
