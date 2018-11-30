<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AgendaRepository")
 * @Vich\Uploadable
 */
class Agenda
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
     * @ORM\Column(type="datetime")
     */
    private $fecha;

    /**
     * @ORM\Column(type="text")
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $imagen;

     /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="agendas_images", fileNameProperty="imagen")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tematica", inversedBy="agendas")
     * * @Assert\Count(
     *      min = 1,
     *      minMessage = "Debe seleccionar al menos una tematica"
     * )
     */
    private $tematicas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="agendas")
     */
    private $ong;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="agendas")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $publicado = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NoticiaComentario", mappedBy="agenda" , orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $comentarios;

    public function __construct()
    {
        $this->tematicas = new ArrayCollection();
        $this->comentarios = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->titulo;
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

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

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


    public function getImagen(): ?string
    {
        return $this->imagen;
    }

    public function setImagen(?string $imagen): self
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
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

    public function getPublicado(): ?bool
    {
        return $this->publicado;
    }

    public function setPublicado(bool $publicado): self
    {
        $this->publicado = $publicado;

        return $this;
    }

    /**
     * @return Collection|NoticiaComentario[]
     */
    public function getComentarios(): Collection
    {
        return $this->comentarios;
    }

    public function addComentario(NoticiaComentario $comentario): self
    {
        if (!$this->comentarios->contains($comentario)) {
            $this->comentarios[] = $comentario;
            $comentario->setAgenda($this);
        }

        return $this;
    }

    public function removeComentario(NoticiaComentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getAgenda() === $this) {
                $comentario->setAgenda(null);
            }
        }

        return $this;
    }
}
