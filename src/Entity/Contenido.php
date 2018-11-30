<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContenidoRepository")
 */
class Contenido
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $codigo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contenido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texto;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resource", mappedBy="contenido", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $imagenes;

    public function __construct()
    {
        $this->imagenes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }

    public function setCodigo(string $codigo): self
    {
        $this->codigo = $codigo;

        return $this;
    }

    public function getContenido(): ?string
    {
        return $this->contenido;
    }

    public function setContenido(?string $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * @return Collection|Resource[]
     */
    public function getImagenes(): Collection
    {
        return $this->imagenes;
    }

    public function addImagene(Resource $imagene): self
    {
        if (!$this->imagenes->contains($imagene)) {
            $this->imagenes[] = $imagene;
            $imagene->setContenido($this);
        }

        return $this;
    }

    public function removeImagene(Resource $imagene): self
    {
        if ($this->imagenes->contains($imagene)) {
            $this->imagenes->removeElement($imagene);
            // set the owning side to null (unless already changed)
            if ($imagene->getContenido() === $this) {
                $imagene->setContenido(null);
            }
        }

        return $this;
    }
}
