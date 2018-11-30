<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Post
 *
 * @ORM\Table(name="programa")
 * @ORM\Entity(repositoryClass="App\Repository\ProgramaRepository")
 * @Vich\Uploadable
 */
class Programa
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
     * @ORM\Column(name="titulo", type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $titulo;

    /**
     * @var string
     *
     * @ORM\Column(name="resumen", type="text", nullable=true)
     */
    private $resumen;

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
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="programas_images", fileNameProperty="imagen")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="programa", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $resources;

    /**
     * @ORM\OneToMany(targetEntity="Documento", mappedBy="programa", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $documentos;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=255, nullable=true)
     */
    private $mail;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="programas")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tematica", inversedBy="programas")
     */
    private $tematicas;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $pagina;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pagina_nombre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="programas")
     */
    private $ong;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NoticiaComentario", mappedBy="programa", orphanRemoval=true, cascade={"persist", "remove"})
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
     * @return Programa
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
     * Set titulo
     *
     * @param string $titulo
     *
     * @return Programa
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
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Programa
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Programa
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set resumen
     *
     * @param string $resumen
     *
     * @return Programa
     */
    public function setResumen($resumen)
    {
        $this->resumen = $resumen;

        return $this;
    }

    /**
     * Get resumen
     *
     * @return string
     */
    public function getResumen()
    {
        return $this->resumen;
    }

    /**
     * Set desarrollo
     *
     * @param string $desarrollo
     *
     * @return Programa
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Programa
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
     * @return Programa
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
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Programa
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
     * @return Programa
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
     * @return Programa
     */
    public function addResource(Resource $resource)
    {
        $resource->setPrograma($this);
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
     * @return Programa
     */
    public function addDocumento(Documento $documento)
    {
        $documento->setPrograma($this);
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getPagina(): ?string
    {
        return $this->pagina;
    }

    public function setPagina(?string $pagina): self
    {
        $this->pagina = $pagina;

        return $this;
    }

    public function getPaginaNombre(): ?string
    {
        return $this->pagina_nombre;
    }

    public function setPaginaNombre(?string $pagina_nombre): self
    {
        $this->pagina_nombre = $pagina_nombre;

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
            $comentario->setPrograma($this);
        }

        return $this;
    }

    public function removeComentario(NoticiaComentario $comentario): self
    {
        if ($this->comentarios->contains($comentario)) {
            $this->comentarios->removeElement($comentario);
            // set the owning side to null (unless already changed)
            if ($comentario->getPrograma() === $this) {
                $comentario->setPrograma(null);
            }
        }

        return $this;
    }


}
