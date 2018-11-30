<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
// DON'T forget this use statement!!!
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * SubTematica
 *
 * @ORM\Table(name="sub_tematica")
 * @ORM\Entity
 * @UniqueEntity(
 *     fields={"tematica", "nombre"},
 *     errorPath="nombre",
 *     message="El nombre ya esta siendo utilizado."
 * )
 */
class SubTematica
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
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="Tematica", inversedBy="subTematicas")
     * @ORM\JoinColumn(name="tematica_id", referencedColumnName="id")
     */
    private $tematica;

    /**
     * @var integer
     *
     * @ORM\Column(name="orden", type="integer", nullable=true)
     */
    private $orden;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $publicado = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ong", mappedBy="subTematicas")
     */
    private $ongs;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ongs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function getNombreMasTematica()
    {
        return $this->getNombre() . " - " . $this->getTematica()->getNombre();
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
     * Set tematica
     *
     * @param Tematica $tematica
     *
     * @return SubTematica
     */
    public function setTematica(Tematica $tematica = null)
    {
        $this->tematica = $tematica;

        return $this;
    }

    /**
     * Get tematica
     *
     * @return tematica
     */
    public function getTematica()
    {
        return $this->tematica;
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
     * Set publicado
     *
     * @param string $publicado
     *
     * @return SubTematica
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
            $ong->addSubTematica($this);
        }

        return $this;
    }

    public function removeOng(Ong $ong): self
    {
        if ($this->ongs->contains($ong)) {
            $this->ongs->removeElement($ong);
            $ong->removeSubTematica($this);
        }

        return $this;
    }

}

