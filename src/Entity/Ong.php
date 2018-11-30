<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OngRepository")
 */
class Ong
{
    private $formasJuridicas = [
        'ASOCIACIÓN CIVIL' => 0,
        'COMUNIDAD INDIGENA' => 1,
        'COOPERATIVA' => 2,
        'FUNDACIÓN' => 3,
        'GRUPO COMUNITARIO' => 4,
        'GRUPO DE PERSONAS' => 5,
        'MUTUAL' => 6,
        'NO ESPECIFICA' => 7,
        'SOCIEDAD DE FOMENTO' => 8,
    ];
    private $localidades = [
        'Resistencia' => 1,
        'Avia Terai'=> 3,
        'Barranqueras'=> 4,
        'Barrio los Pescadores'=> 5,
        'Basail'=> 6,
        'Campo largo'=> 7,
        'Capitán Solari'=> 8,
        'Charadai'=> 9,
        'Charata'=> 10,
        'Chorotis'=> 11,
        'Ciervo Petiso'=> 12,
        'Colonia Aborigen'=> 13,
        'Colonia Baranda'=> 14,
        'Colonia Benitez'=> 15,
        'Colonia Elisa'=> 16,
        'Colonia Popular'=> 17,
        'Colonias Unidas'=> 18,
        'Concepción del Bermejo'=> 19,
        'Coronel Du Graty'=> 20,
        'Corzuela'=> 21,
        'Cote Lai'=> 22,
        'El Espinillo'=> 23,
        'El Sauzal'=> 24,
        'El Sauzalito'=> 25,
        'Enrique Urien'=> 26,
        'Estación General Obligado'=> 27,
        'Fontana'=>28,
        'Fortín Las Chuñas'=> 29,
        'Fortín Lavalle'=> 30,
        'Fuerte ESperanza'=> 31,
        'Gancedo'=> 32,
        'General Capdevilla'=> 33,
        'General José de San Martín'=> 34,
        'General Pinedo'=> 35,
        'General Vedia'=> 36,
        'Haumonia'=> 37,
        'Hermoso Campo'=> 38,
        'Horquilla'=> 39,
        'Ingeniero Barnet'=> 40,
        'Isla del Cerrito'=> 41,
        'Itín'=> 42,
        'Juan José Castelli'=> 43,
        'La Clotilde'=> 44,
        'La Eduvigis'=> 45,
        'La Escondida'=> 46,
        'La Leonesa'=> 47,
        'La Sábana'=> 48,
        'La Tigra'=> 49,
        'La Verde'=> 50,
        'Laguna Blanca'=> 51,
        'Laguna Limpia'=> 52,
        'Lapachito'=> 53,
        'Las Breñas'=> 54,
        'Las Garcitas'=> 55,
        'Las Palmas'=> 56,
        'Los Frentones'=> 57,
        'Machagai'=> 58,
        'Makallé'=> 59,
        'Margarita Belén'=> 60,
        'Mesón de Fierro'=> 61,
        'Miraflores'=> 62,
        'Napalpí'=> 63,
        'Napenay'=> 64,
        'Nueva Pompeya'=> 65,
        'Pampa Almirón'=> 66,
        'Pampa del Indio'=> 67,
        'Pampa del Infierno'=> 68,
        'Pampa Landriel'=> 69,
        'Presidencia de la Plaza'=> 70,
        'Presidencia Roque Sáenz Peña'=> 71,
        'Puerto Bermejo'=> 72,
        'Puerto Eva Perón'=> 73,
        'Puerto Lavalle'=> 74,
        'Puerto Tirol'=> 75,
        'Puerto Vilelas'=> 76,
        'Quitilipi'=> 77,
        'Río Muerto'=> 78, 
        'Samuhú'=> 79,
        'San Bernardo'=> 80,
        'Santa Sylvina'=> 81,
        'Selvas del Río de Oro'=> 82,
        'Taco Pozo'=> 83,
        'Tres Isletas'=> 84,
        'Venados Grandes'=> 85,
        'Villa Angela'=> 86,
        'Villa Berthet'=> 87,
        'Villa El Palmar'=> 88,
        'Villa Río Bermejito'=> 89,
        'Wichi'=> 90,
        'Zaparinqui'=> 91
    ];

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
     * @Assert\NotBlank()
     */
    private $nombreOrganizacion;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $formaJuridica;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $matricula;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $cuit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $direccion;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $localidad;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $codigoPostal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\d/",
     *     message="Unicamente números"
     * )
     */
    private $celular;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $apellido;

    /**
     * @ORM\Column(type="string", length=200)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $observaciones;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tematica", inversedBy="ongs")
     * @Assert\Count(
     *      min = 1,
     *      minMessage = "Debe seleccionar al menos una tematica"
     * )
     */
    private $tematicas;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\SubTematica", inversedBy="ongs")
     */
    private $subTematicas;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="ong", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $esJurisdiccion = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Noticia", mappedBy="ong")
     */
    private $noticias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Programa", mappedBy="ong")
     */
    private $programas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Agenda", mappedBy="ong")
     */
    private $agendas;

    /**
     * @ORM\Column(type="boolean", options={"default" : 0})
     */
    private $publicado = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComentarioDebate", mappedBy="ong")
     */
    private $comentarioDebates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Debate", mappedBy="ong")
     */
    private $debates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="ongColaborator", cascade={"persist"})
     */
    private $colaborators;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Colaborator", mappedBy="ong", orphanRemoval=true, cascade={"persist"})
     */
    private $colaboratorsCuils;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $correo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facebook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $twitter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $instagram;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OngTipoAporte", inversedBy="ongs")
     */
    private $aportes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Documento", mappedBy="ong", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid
     */
    private $documentos;

    public function __construct()
    {
        $this->tematicas = new ArrayCollection();
        $this->subTematicas = new ArrayCollection();
        $this->noticias = new ArrayCollection();
        $this->programas = new ArrayCollection();
        $this->agendas = new ArrayCollection();
        $this->comentarioDebates = new ArrayCollection();
        $this->debates = new ArrayCollection();
        $this->colaborators = new ArrayCollection();
        $this->colaboratorsCuils = new ArrayCollection();
        $this->aportes = new ArrayCollection();
        $this->documentos = new ArrayCollection();
    }

    public function getFormasJuridicasArray()
    {
        return $this->formasJuridicas;
    }
    public function getFormasJuridicasString()
    {
        return array_search($this->formaJuridica, $this->formasJuridicas);
    }

    public function getLocalidades()
    {
        return $this->localidades;
    }
    public function getLocalidadesString()
    {
        return array_search($this->localidad, $this->localidades);
    }

    public function getContacto()
    {
        return $this->apellido.", ".$this->nombre;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombreOrganizacion(): ?string
    {
        return $this->nombreOrganizacion;
    }

    public function setNombreOrganizacion(string $nombreOrganizacion): self
    {
        $this->nombreOrganizacion = $nombreOrganizacion;

        return $this;
    }

    public function getFormaJuridica(): ?int
    {
        return $this->formaJuridica;
    }

    public function setFormaJuridica(?int $formaJuridica): self
    {
        $this->formaJuridica = $formaJuridica;

        return $this;
    }

    public function getMatricula(): ?string
    {
        return $this->matricula;
    }

    public function setMatricula(?string $matricula): self
    {
        $this->matricula = $matricula;

        return $this;
    }

    public function getCuit(): ?string
    {
        return $this->cuit;
    }

    public function setCuit(?string $cuit): self
    {
        $this->cuit = $cuit;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): self
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getLocalidad(): ?int
    {
        return $this->localidad;
    }

    public function setLocalidad(int $localidad): self
    {
        $this->localidad = $localidad;

        return $this;
    }

    public function getCodigoPostal(): ?string
    {
        return $this->codigoPostal;
    }

    public function setCodigoPostal(string $codigoPostal): self
    {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getCelular(): ?string
    {
        return $this->celular;
    }

    public function setCelular(string $celular): self
    {
        $this->celular = $celular;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): self
    {
        $this->observaciones = $observaciones;

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

    /**
     * @return Collection|SubTematica[]
     */
    public function getSubTematicas(): Collection
    {
        return $this->subTematicas;
    }

    public function addSubTematica(SubTematica $subTematica): self
    {
        if (!$this->subTematicas->contains($subTematica)) {
            $this->subTematicas[] = $subTematica;
        }

        return $this;
    }

    public function removeSubTematica(SubTematica $subTematica): self
    {
        if ($this->subTematicas->contains($subTematica)) {
            $this->subTematicas->removeElement($subTematica);
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

    public function getEsJurisdiccion(): ?bool
    {
        return $this->esJurisdiccion;
    }

    public function setEsJurisdiccion(bool $esJurisdiccion): self
    {
        $this->esJurisdiccion = $esJurisdiccion;

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
            $noticia->setOng($this);
        }

        return $this;
    }

    public function removeNoticia(Noticia $noticia): self
    {
        if ($this->noticias->contains($noticia)) {
            $this->noticias->removeElement($noticia);
            // set the owning side to null (unless already changed)
            if ($noticia->getOng() === $this) {
                $noticia->setOng(null);
            }
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
            $programa->setOng($this);
        }

        return $this;
    }

    public function removePrograma(Programa $programa): self
    {
        if ($this->programas->contains($programa)) {
            $this->programas->removeElement($programa);
            // set the owning side to null (unless already changed)
            if ($programa->getOng() === $this) {
                $programa->setOng(null);
            }
        }

        return $this;
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
            $agenda->setOng($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->agendas->contains($agenda)) {
            $this->agendas->removeElement($agenda);
            // set the owning side to null (unless already changed)
            if ($agenda->getOng() === $this) {
                $agenda->setOng(null);
            }
        }

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
     * @return Collection|ComentarioDebate[]
     */
    public function getComentarioDebates(): Collection
    {
        return $this->comentarioDebates;
    }

    public function addComentarioDebate(ComentarioDebate $comentarioDebate): self
    {
        if (!$this->comentarioDebates->contains($comentarioDebate)) {
            $this->comentarioDebates[] = $comentarioDebate;
            $comentarioDebate->setOng($this);
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getColaborators(): Collection
    {
        return $this->colaborators;
    }

    public function addColaborator(User $colaborator): self
    {
        if (!$this->colaborators->contains($colaborator)) {
            $this->colaborators[] = $colaborator;
            $colaborator->setOngColaborator($this);
        }

        return $this;
    }

    public function removeComentarioDebate(ComentarioDebate $comentarioDebate): self
    {
        if ($this->comentarioDebates->contains($comentarioDebate)) {
            $this->comentarioDebates->removeElement($comentarioDebate);
            // set the owning side to null (unless already changed)
            if ($comentarioDebate->getOng() === $this) {
                $comentarioDebate->setOng(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Debate[]
     */
    public function getDebates(): Collection
    {
        return $this->debates;
    }

    public function addDebate(Debate $debate): self
    {
        if (!$this->debates->contains($debate)) {
            $this->debates[] = $debate;
            $debates>setOng($this);
        }

        return $this;
    }

    public function removeDebates(Debate $debate): self
    {
        if ($this->debates->contains($debate)) {
            $this->debates->removeElement($debate);
            // set the owning side to null (unless already changed)
            if ($debate->getOng() === $this) {
                $debate->setOng(null);
            }
        }

        return $this;
    }

    public function removeColaborator(User $colaborator): self
    {
        if ($this->colaborators->contains($colaborator)) {
            $this->colaborators->removeElement($colaborator);
            // set the owning side to null (unless already changed)
            if ($colaborator->getOngColaborator() === $this) {
                $colaborator->setOngColaborator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Colaborator[]
     */
    public function getColaboratorsCuils(): Collection
    {
        return $this->colaboratorsCuils;
    }

    public function addColaboratorsCuil(Colaborator $colaboratorsCuil): self
    {
        if (!$this->colaboratorsCuils->contains($colaboratorsCuil)) {
            $this->colaboratorsCuils[] = $colaboratorsCuil;
            $colaboratorsCuil->setOng($this);
        }

        return $this;
    }

    public function removeColaboratorsCuil(Colaborator $colaboratorsCuil): self
    {
        if ($this->colaboratorsCuils->contains($colaboratorsCuil)) {
            $this->colaboratorsCuils->removeElement($colaboratorsCuil);
            // set the owning side to null (unless already changed)
            if ($colaboratorsCuil->getOng() === $this) {
                $colaboratorsCuil->setOng(null);
            }
        }

        return $this;
    }

    public function getCorreo(): ?string
    {
        return $this->correo;
    }

    public function setCorreo(?string $correo): self
    {
        $this->correo = $correo;

        return $this;
    }

    public function getFacebook(): ?string
    {
        return $this->facebook;
    }

    public function setFacebook(?string $facebook): self
    {
        $this->facebook = $facebook;

        return $this;
    }

    public function getTwitter(): ?string
    {
        return $this->twitter;
    }

    public function setTwitter(?string $twitter): self
    {
        $this->twitter = $twitter;

        return $this;
    }

    public function getInstagram(): ?string
    {
        return $this->instagram;
    }

    public function setInstagram(?string $instagram): self
    {
        $this->instagram = $instagram;

        return $this;
    }

    public function __toString()
    {
        return $this->nombreOrganizacion;
    }

    /**
     * @return Collection|OngTipoAporte[]
     */
    public function getAportes(): Collection
    {
        return $this->aportes;
    }

    public function addAporte(OngTipoAporte $aporte): self
    {
        if (!$this->aportes->contains($aporte)) {
            $this->aportes[] = $aporte;
        }

        return $this;
    }

    public function removeAporte(OngTipoAporte $aporte): self
    {
        if ($this->aportes->contains($aporte)) {
            $this->aportes->removeElement($aporte);
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
            $documento->setOng($this);
        }

        return $this;
    }

    public function removeDocumento(Documento $documento): self
    {
        if ($this->documentos->contains($documento)) {
            $this->documentos->removeElement($documento);
            // set the owning side to null (unless already changed)
            if ($documento->getOng() === $this) {
                $documento->setOng(null);
            }
        }

        return $this;
    }
}
