<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OngTipoAporteRepository")
 */
class OngTipoAporte
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
    private $descripcion;

    /**
     * @ORM\Column(type="boolean", options={"default" : 1})
     */
    private $activo = true;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ong", mappedBy="aportes")
     */
    private $ongs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clasificacion;

    public function __construct()
    {
        $this->ongs = new ArrayCollection();
    }

    public function __toString() {
        return $this->descripcion;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getActivo(): ?bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * @return Collection|Ong[]
     */
    public function getOngs(): Collection
    {
        return $this->ongs;
    }

    public function addOng(Ong $ong): self
    {
        if (!$this->ongs->contains($ong)) {
            $this->ongs[] = $ong;
            $ong->addAporte($this);
        }

        return $this;
    }

    public function removeOng(Ong $ong): self
    {
        if ($this->ongs->contains($ong)) {
            $this->ongs->removeElement($ong);
            $ong->removeAporte($this);
        }

        return $this;
    }

    public function getClasificacion(): ?string
    {
        return $this->clasificacion;
    }

    public function setClasificacion(?string $clasificacion): self
    {
        $this->clasificacion = $clasificacion;

        return $this;
    }
}
