<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ComentarioDebateRepository")
 */
class ComentarioDebate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Debate", inversedBy="comentarioDebates")
     */
    private $debate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="comentarioDebates")
     */
    private $ong;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comentarioDebates")
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     */
    private $comentario;

    /**
     * @ORM\Column(type="datetime")
     */
    private $fechaCreacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaModificacion;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDebate(): ?Debate
    {
        return $this->debate;
    }

    public function setDebate(?Debate $debate): self
    {
        $this->debate = $debate;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    public function getFechaModificacion(): ?\DateTimeInterface
    {
        return $this->fechaModificacion;
    }

    public function setFechaModificacion(?\DateTimeInterface $fechaModificacion): self
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }
}
