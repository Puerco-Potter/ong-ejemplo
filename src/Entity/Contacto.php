<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactoRepository")
 */
class Contacto
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $organizacion;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $cuil;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=190)
     */
    private $telefono;

    /**
     * @ORM\Column(type="text")
     */
    private $observaciones;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $inscripcion = false;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $registro = false;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $cuitOng;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getOrganizacion(): ?string
    {
        return $this->organizacion;
    }

    public function setOrganizacion(string $organizacion): self
    {
        $this->organizacion = $organizacion;

        return $this;
    }

    public function getCuil(): ?string
    {
        return $this->cuil;
    }

    public function setCuil(string $cuil): self
    {
        $this->cuil = $cuil;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(string $observaciones): self
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getInscripcion(): ?bool
    {
        return $this->inscripcion;
    }

    public function setInscripcion(bool $inscripcion): self
    {
        $this->inscripcion = $inscripcion;

        return $this;
    }

    public function getRegistro(): ?bool
    {
        return $this->registro;
    }

    public function setRegistro(bool $registro): self
    {
        $this->registro = $registro;

        return $this;
    }

    public function getCuitOng(): ?string
    {
        return $this->cuitOng;
    }

    public function setCuitOng(?string $cuitOng): self
    {
        $this->cuitOng = $cuitOng;

        return $this;
    }
}
