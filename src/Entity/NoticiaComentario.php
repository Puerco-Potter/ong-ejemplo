<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NoticiaComentarioRepository")
 */
class NoticiaComentario
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
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $cerrado = false;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Noticia", inversedBy="comentarios")
     */
    private $noticia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Agenda", inversedBy="comentarios")
     */
    private $agenda;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Programa", inversedBy="comentarios")
     */
    private $programa;

    public function __toString()
    {
        return "(" . $this->updatedAt->format('d/m/Y H:i') . "h) " .$this->descripcion;
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

    public function getCerrado(): ?bool
    {
        return $this->cerrado;
    }

    public function setCerrado(bool $cerrado): self
    {
        $this->cerrado = $cerrado;

        return $this;
    }

    public function getNoticia(): ?Noticia
    {
        return $this->noticia;
    }

    public function setNoticia(?Noticia $noticia): self
    {
        $this->noticia = $noticia;

        return $this;
    }



    public function getAgenda(): ?Agenda
    {
        return $this->agenda;
    }

    public function setAgenda(?Agenda $agenda): self
    {
        $this->agenda = $agenda;

        return $this;
    }

    public function getPrograma(): ?Programa
    {
        return $this->programa;
    }

    public function setPrograma(?Programa $programa): self
    {
        $this->programa = $programa;

        return $this;
    }
}
