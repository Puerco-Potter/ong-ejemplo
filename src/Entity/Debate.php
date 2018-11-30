<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DebateRepository")
 */
class Debate
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
    private $titulo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tematica", inversedBy="debates")
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Debe seleccionar al menos una tematica"
     * )
     */
    private $tematicas;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fechaCreacion;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComentarioDebate", mappedBy="debate")
     */
    private $comentariosDebates;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="debates")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="debates")
     */
    private $ong;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descripcion;

    public function __construct()
    {
        $this->tematicas = new ArrayCollection();
        $this->debates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * @return Collection|Tematica[]
     */
    public function getTematicas(): Collection
    {
        return $this->tematicas;
    }

    public function addTematica(Tematica $tematica): self
    {
        if (!$this->tematicas->contains($tematica)) {
            $this->tematicas[] = $tematica;
        }

        return $this;
    }

    public function removeTematica(Tematica $tematica): self
    {
        if ($this->tematicas->contains($tematica)) {
            $this->tematicas->removeElement($tematica);
        }

        return $this;
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


    public function getFechaCreacion(): ?\DateTimeInterface
    {
        return $this->fechaCreacion;
    }

    public function setFechaCreacion(\DateTimeInterface $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }


    /**
     * @return Collection|ComentarioDebate[]
     */
    public function getComentariosDebates(): Collection
    {
        return $this->comentariosDebates;
    }

    public function addComentariosDebate(ComentarioDebate $comentariosDebate): self
    {
        if (!$this->comentariosDebates->contains($comentariosDebate)) {
            $this->comentariosDebates[] = $comentariosDebate;
            $ong->comentariosDebate($this);
        }

        return $this;
    }

    public function removeComentariosDebate(ComentarioDebate $comentariosDebate): self
    {
        if ($this->comentariosDebates->contains($comentariosDebate)) {
            $this->comentariosDebates->removeElement($comentariosDebate);
            // set the owning side to null (unless already changed)
            if ($comentariosDebate->getDebate() === $this) {
                $comentariosDebate->setDebate(null);
            }
        }

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
