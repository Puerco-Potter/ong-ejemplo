<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Resource
 *
 * @ORM\Table(name="resource")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Resource
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
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="epigrafe", type="text", nullable=true)
     */
    private $epigrafe;

    /**
     * @var Noticia
     *
     * @ORM\ManyToOne(targetEntity="Noticia", inversedBy="resources")
     * @ORM\JoinColumn(name="noticia_id", referencedColumnName="id")
     */
    private $noticia;

    /**
     * @var Programa
     *
     * @ORM\ManyToOne(targetEntity="Programa", inversedBy="resources")
     * @ORM\JoinColumn(name="programa_id", referencedColumnName="id")
     */
    private $programa;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="resource_image", fileNameProperty="path")
     * 
     * @var File
     * @Assert\Image(
     *     maxSize = "2M",
     *     mimeTypes={"image/jpeg", "image/png"}
     * )
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     * @Assert\NotNull
     */
    private $updatedAt;
    
    /**
     * @var Tematica
     *
     * @ORM\ManyToOne(targetEntity="Tematica", inversedBy="resources")
     * @ORM\JoinColumn(name="tematica_id", referencedColumnName="id")
     */
    private $tematica;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Contenido", inversedBy="imagenes")
     */
    private $contenido;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->updatedAt = new \DateTime('now');
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
     * Set path
     *
     * @param string $path
     *
     * @return Resource
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set epigrafe
     *
     * @param string $epigrafe
     *
     * @return Resource
     */
    public function setEpigrafe($epigrafe)
    {
        $this->epigrafe = $epigrafe;

        return $this;
    }

    /**
     * Get epigrafe
     *
     * @return string
     */
    public function getEpigrafe()
    {
        return $this->epigrafe;
    }

    /**
     * Set noticia
     *
     * @param Noticia $noticia
     *
     * @return Resource
     */
    public function setNoticia(Noticia $noticia = null)
    {
        $this->noticia = $noticia;

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

    /**
     * Set programa
     *
     * @param Programa $programa
     *
     * @return Resource
     */
    public function setPrograma(Programa $programa = null)
    {
        $this->programa = $programa;

        return $this;
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

    /**
     * Set Tematica
     *
     * @param Tematica $tematica
     *
     * @return Resource
     */
    public function setTematica(Tematica $tematica = null)
    {
        $this->tematica = $tematica;

        return $this;
    }

    /**
     * Get tematica
     *
     * @return Tematica
     */
    public function getTematica()
    {
        return $this->tematica;
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Resource
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getContenido(): ?Contenido
    {
        return $this->contenido;
    }

    public function setContenido(?Contenido $contenido): self
    {
        $this->contenido = $contenido;

        return $this;
    }
}
