<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Documento
 *
 * @ORM\Table(name="documento")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Documento
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
     * @ORM\Column(name="epigrafe", type="text", nullable=false)
     * @Assert\NotBlank()
     */
    private $epigrafe;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Programa", inversedBy="documentos")
     */
    private $programa;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Noticia", inversedBy="documentos")
     */
    private $noticia;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Tematica", inversedBy="documentos")
     */
    private $tematica;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="documentos")
     */
    private $ong;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="documento_image", fileNameProperty="path")
     * 
     * @var File
     * @Assert\File(
     *     maxSize = "15M",
     *     mimeTypesMessage = "Solo se permiten documentos PDF y archivos docx"
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
     * @return Documento
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
     * @return Documento
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
     * Set programa
     *
     * @param Programa $programa
     *
     * @return Documento
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
     * Set noticia
     *
     * @param Noticia $noticia
     *
     * @return Documento
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
     * @return Documento
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
