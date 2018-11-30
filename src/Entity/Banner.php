<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Noticia
 *
 * @ORM\Table(name="banner")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Banner
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
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;

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
     * @Vich\UploadableField(mapping="banners_images", fileNameProperty="imagen")
     * 
     * @var File
     */
    private $imageFile;


    /**
     * @ORM\ManyToOne(targetEntity="Noticia")
     * @ORM\JoinColumn(name="noticia_id", referencedColumnName="id")
     */
    private $noticia;

    /**
     * @ORM\ManyToOne(targetEntity="Programa")
     * @ORM\JoinColumn(name="programa_id", referencedColumnName="id")
     */
    private $programa;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=255)
     */
    private $tipo;

     /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;


    
    /**
     * Constructor
     */
    public function __construct()
    {

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
     * @return Banner
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Banner
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion  = $descripcion;

        return $this;
    }

     /**
    * Get descripcion
    *
    * @return string
    */
    public function getdescripcion()
    {
        return $this->descripcion;
    }


    /**
     * Get programa
     *
     * @return Programa 
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /*
    * Set programa
    *
    * @param Programa $programa
    *
    * @return Banner
    */
   public function setPrograma($programa)
   {
       $this->programa = $programa;

       return $this;
   }

   /**
     * Get noticia
     *
     * @return Noticia 
     */
    public function getNoticia()
    {
        return $this->noticia;
    }

    /*
    * Set noticia
    *
    * @param Noticia $noticia
    *
    * @return Banner
    */
   public function setNoticia($noticia)
   {
       $this->noticia = $noticia;

       return $this;
   }

   /**
     * Get tipo
     *
     * @return String 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /*
    * Set tipo
    *
    * @param string $tipo
    *
    * @return Banner
    */
   public function setTipo($tipo)
   {
       $this->tipo = $tipo;

       return $this;
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

   
}
