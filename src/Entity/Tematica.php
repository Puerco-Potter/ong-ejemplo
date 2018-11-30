<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
// DON'T forget this use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Tematica
 *
 * @ORM\Table(name="tematica")
 * @ORM\Entity
 * @UniqueEntity("nombre")
 * @Vich\Uploadable
 */
class Tematica
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=150, unique=true)
     */
    private $nombre;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * One Product has Many Features.
     * @ORM\OneToMany(targetEntity="SubTematica", mappedBy="tematica")
     */
    private $subTematicas;
   
    /**
     * @var string
     *
     * @ORM\Column(name="imagen", type="string", length=255, nullable=true)
     */
    private $imagen;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="tematicas_images", fileNameProperty="imagen")
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
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $publicado = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ong", mappedBy="tematicas")
     */
    private $ongs;

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
     * @var string
     *
     * @ORM\Column(name="imagen_principal", type="string", length=255, nullable=true)
     */
    private $imagenPrincipal;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="tematicas_images", fileNameProperty="imagenPrincipal")
     * 
     * @var File
     */
    private $imageFilePrincipal;

    /**
     * @ORM\OneToMany(targetEntity="Resource", mappedBy="tematica", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $resources;

    /**
     * @ORM\OneToMany(targetEntity="Documento", mappedBy="tematica", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $documentos;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Agenda", mappedBy="tematicas")
     */
    private $agendas;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Noticia", mappedBy="tematicas")
     */
    private $noticias;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Programa", mappedBy="tematicas")
     */
    private $programas;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Debate", mappedBy="Tematica")
     */
    private $debates;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subTematicas = new ArrayCollection();
        $this->ongs = new ArrayCollection();
        $this->documentos = new ArrayCollection();
        $this->resources = new ArrayCollection();
        $this->agendas = new ArrayCollection();
        $this->noticias = new ArrayCollection();
        $this->programas = new ArrayCollection();
        $this->debates = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }


    /**
     * Set orden
     *
     * @param int orden
     *
     * @return orden
     */
    public function setOrden($orden)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden
     *
     * @return int
     */
    public function getOrden()
    {
        return $this->orden;
    }
    
    /**
     * Add tematica
     *
     * @param SubTematica $subTematica
     *
     * @return SubTematica
     */
    public function addSubTematica(SubTematica $subTematica)
    {
        $this->subTematicas[] = $subTematica;

        return $this;
    }

    /**
     * Remove subTematica
     *
     * @param SubTematica $subTematica
     */
    public function removeSubTematicas(SubTematica $subTematica)
    {
        $this->subTematicas->removeElement($subTematica);
    }

    /**
     * Get subTematicas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubTematicas()
    {
        return $this->subTematicas;
    }



    /**
     * Set imagen
     *
     * @param string $imagen
     *
     * @return Tematica
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
     * @return Tematica
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
     * Set publicado
     *
     * @param string $publicado
     *
     * @return Tematica
     */
    public function setPublicado($publicado)
    {
        $this->publicado = $publicado;

        return $this;
    }

    /**
     * Get publicado
     *
     * @return string
     */
    public function getPublicado()
    {
        return $this->publicado;
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
            $ong->addTematica($this);
        }

        return $this;
    }

    public function removeOng(Ong $ong): self
    {
        if ($this->ongs->contains($ong)) {
            $this->ongs->removeElement($ong);
            $ong->removeTematica($this);
        }

        return $this;
    }

 

    /**
     * @return Collection|Resource[]
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resources->contains($resource)) {
            $this->resources[] = $resource;
            $resource->setTematica($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        if ($this->resources->contains($resource)) {
            $this->resources->removeElement($resource);
            // set the owning side to null (unless already changed)
            if ($resource->getTematica() === $this) {
                $resource->setTematica(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Documento[]
     */
    public function getDocumentos(): Collection
    {
        return $this->documentos;
    }

    public function addDocumento(Documento $documento): self
    {
        if (!$this->documentos->contains($documento)) {
            $this->documentos[] = $documento;
            $documento->setTematica($this);
        }

        return $this;
    }

    public function removeDocumento(Documento $documento): self
    {
        if ($this->documentos->contains($documento)) {
            $this->documentos->removeElement($documento);
            // set the owning side to null (unless already changed)
            if ($documento->getTematica() === $this) {
                $documento->setTematica(null);
            }
        }

        return $this;
    }

     /**
     * Set imagenPrincipal
     *
     * @param string $imagenPrincipal
     *
     * @return Tematica
     */
    public function setImagenPrincipal($imagenPrincipal)
    {
        $this->imagenPrincipal = $imagenPrincipal;

        return $this;
    }

    /**
     * Get imagenPrincipal
     *
     * @return string
     */
    public function getImagenPrincipal()
    {
        return $this->imagenPrincipal;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imagenFilePrincipal
     *
     * @return Tematica
     */
    public function setImageFilePrincipal(File $image = null)
    {
        $this->imageFilePrincipal = $image;

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
    public function getImageFilePrincipal()
    {
        return $this->imageFilePrincipal;
    }


    /**
     * Set resumen
     *
     * @param string $resumen
     *
     * @return Tematica
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
     * @return Tematica
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
     * @return Collection|Agenda[]
     */
    public function getAgendas(): Collection
    {
        return $this->agendas;
    }

    public function addAgenda(Agenda $agenda): self
    {
        if (!$this->agendas->contains($agenda)) {
            $this->agendas[] = $agenda;
            $agenda->addTematica($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->agendas->contains($agenda)) {
            $this->agendas->removeElement($agenda);
            $agenda->removeTematica($this);
        }

        return $this;
    }

    /**
     * @return Collection|Noticia[]
     */
    public function getNoticias(): Collection
    {
        return $this->noticias;
    }

    public function addNoticia(Noticia $noticia): self
    {
        if (!$this->noticias->contains($noticia)) {
            $this->noticias[] = $noticia;
            $noticia->addTematica($this);
        }

        return $this;
    }

    public function removeNoticia(Noticia $noticia): self
    {
        if ($this->noticias->contains($noticia)) {
            $this->noticias->removeElement($noticia);
            $noticia->removeTematica($this);
        }

        return $this;
    }

    /**
     * @return Collection|Programa[]
     */
    public function getProgramas(): Collection
    {
        return $this->programas;
    }

    public function addPrograma(Programa $programa): self
    {
        if (!$this->programas->contains($programa)) {
            $this->programas[] = $programa;
            $programa->addTematica($this);
        }

        return $this;
    }

    public function removePrograma(Programa $programa): self
    {
        if ($this->programas->contains($programa)) {
            $this->programas->removeElement($programa);
            $programa->removeTematica($this);
        }

        return $this;
    }

    /**
     * @return Collection|Debate[]
     */
    public function getDebate(): Collection
    {
        return $this->debates;
    }

    public function addDebate(Debate $debate): self
    {
        if (!$this->debates->contains($debate)) {
            $this->debates[] = $debate;
            $debate->addTematica($this);
        }

        return $this;
    }

    public function removeDebate(Debate $debate): self
    {
        if ($this->debates->contains($debate)) {
            $this->debates->removeElement($debate);
            $debate->removeTematica($this);
        }

        return $this;
    }

    

   
}

