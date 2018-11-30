<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Noticia
 *
 * @ORM\Table(name="noticia")
 * @ORM\Entity(repositoryClass="App\Repository\NoticiaRepository")
 * @Vich\Uploadable
 */
class Noticia
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $publicado = false;

    /**
     * @var string
     *
     * @ORM\Column(name="volanta", type="string", length=255, nullable=true)
     */
    private $volanta;

    /**
     * @var string
     *
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="bajada", type="text", nullable=true)
     */
    private $bajada;

    /**
     * @var string
     *
     * @ORM\Column(name="desarrollo", type="text", nullable=true)
     */
    private $desarrollo;

    /**
     * @var \DateTime $fechaCreacion
     * @ORM\Column(name="fechaCreacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime $fechaModificacion
     * @ORM\Column(name="fechaModificacion", type="datetime", nullable=true)
     */
    private $fechaModificacion;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $mostrarTitulo = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $mostrarBajada = false;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $mostrarVolanta = false;

    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;


    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="noticias_images", fileNameProperty="imagen")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="noticia", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $resources;

    /**
     * @ORM\OneToMany(targetEntity="Documento", mappedBy="noticia", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $documentos;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="link_video", type="string", length=500, nullable=true)
     * 
     */
    private $linkVideo;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tematica", inversedBy="noticias")
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Debe seleccionar al menos una tematica"
     * )
     */
    private $tematicas;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="noticias")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="noticias")
     */
    private $ong;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NoticiaComentario", mappedBy="noticia", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $comentarios;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->documentos = new ArrayCollection();
        $this->tematicas = new ArrayCollection();
        $this->comentarios = new ArrayCollection();

    }

    public function __toString() {
        return $this->getTitulo();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    

    /**
     * Set publicado
     *
     * @param boolean $publicado
     *
     * @return Noticia
     */
    public function setPublicado($publicado)
    {
        $this->publicado = $publicado;

        return $this;
    }

    /**
     * Get publicado
     *
     * @return boolean
     */
    public function getPublicado()
    {
        return $this->publicado;
    }

    /**
     * Set volanta
     *
     * @param string $volanta
     *
     * @return Noticia
     */
    public function setVolanta($volanta)
    {
        $this->volanta = $volanta;

        return $this;
    }

    /**
     * Get volanta
     *
     * @return string
     */
    public function getVolanta()
    {
        return $this->volanta;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Noticia
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set bajada
     *
     * @param string $bajada
     *
     * @return Noticia
     */
    public function setBajada($bajada)
    {
        $this->bajada = $bajada;

        return $this;
    }

    /**
     * Get bajada
     *
     * @return string
     */
    public function getBajada()
    {
        return $this->bajada;
    }

    /**
     * Set desarrollo
     *
     * @param string $desarrollo
     *
     * @return Noticia
     */
    public function setDesarrollo($desarrollo)
    {
        $this->desarrollo = $desarrollo;

        return $this;
    }

    /**
     * Get desarrollo
     *
     * @return string
     */
    public function getDesarrollo()
    {
        return $this->desarrollo;
    }

    /**
     * Set linkVideo
     *
     * @param string $linkVideo
     *
     * @return Noticia
     */
    public function setLinkVideo($linkVideo)
    {
        $this->linkVideo = $linkVideo;

        return $this;
    }

    /**
     * Get linkVideo
     *
     * @return string
     */
    public function getLinkVideo()
    {
        return $this->linkVideo;
    }
    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Noticia
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }


    /**
     * Set fechaModificacion
     *
     * @param \DateTime $fechaModificacion
     *
     * @return Noticia
     */
    public function setFechaModificacion($fechaModificacion)
    {
        $this->fechaModificacion = $fechaModificacion;

        return $this;
    }

    /**
     * Get fechaModificacion
     *
     * @return \DateTime
     */
    public function getFechaModificacion()
    {
        return $this->fechaModificacion;
    }

    /**
     * Set mostrarTitulo
     *
     * @param boolean $mostrarTitulo
     *
     * @return Noticia
     */
    public function setMostrarTitulo($mostrarTitulo)
    {
        $this->mostrarTitulo = $mostrarTitulo;

        return $this;
    }

    /**
     * Get mostrarTitulo
     *
     * @return boolean
     */
    public function getMostrarTitulo()
    {
        return $this->mostrarTitulo;
    }

    /**
     * Set mostrarBajada
     *
     * @param boolean $mostrarVolanta
     *
     * @return Noticia
     */
    public function setMostrarVolanta($mostrarVolanta)
    {
        $this->mostrarVolanta = $mostrarVolanta;

        return $this;
    }

    /**
     * Get mostrarVolanta
     *
     * @return boolean
     */
    public function getMostrarVolanta()
    {
        return $this->mostrarVolanta;
    }

    /**
     * Set mostrarBajada
     *
     * @param boolean $mostrarBajada
     *
     * @return Noticia
     */
    public function setMostrarBajada($mostrarBajada)
    {
        $this->mostrarBajada = $mostrarBajada;

        return $this;
    }

    /**
     * Get mostrarBajada
     *
     * @return boolean
     */
    public function getMostrarBajada()
    {
        return $this->mostrarBajada;
    }


    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Noticia
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get imagen
     *
     * @return string
     */
    public function getImagen()
    {
        return $this->imagen;
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
     * Add resource
     *
     * @param Resource $resource
     *
     * @return Noticia
     */
    public function addResource(Resource $resource)
    {
        $resource->setNoticia($this);
        $this->resources[] = $resource;

        return $this;
    }

    /**
     * Remove resource
     *
     * @param Resource $resource
     */
    public function removeResource(Resource $resource)
    {
        $this->resources->removeElement($resource);
    }

    /**
     * Get resources
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * Add documento
     *
     * @param Documento $documento
     *
     * @return Noticia
     */
    public function addDocumento(Documento $documento)
    {
        $documento->setNoticia($this);
        $this->documentos[] = $documento;

        return $this;
    }

    /**
     * Remove documento
     *
     * @param Documento $documento
     */
    public function removeDocumento(Documento $documento)
    {
        $this->documentos->removeElement($documento);
    }

    /**
     * Get documentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocumentos()
    {
        return $this->documentos;
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
            $comentario->setNoticia($this);
        }

        return $this;
    }

    public function removeComentario(NoticiaComentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getNoticia() === $this) {
                $comentario->setNoticia(null);
            }
        }

        return $this;
    }

}
