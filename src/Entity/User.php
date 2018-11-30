<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;

//con el atribute override hago que la contrasena pueda ser nula
/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * 
 * @AttributeOverrides({
 *     @AttributeOverride(name="password",
 *         column=@ORM\Column(
 *             type="string",
 *             length=255,
 *             nullable=true
 *         )
 *     )
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Ong", mappedBy="user", cascade={"persist"})
     */
    private $ong;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $refreshToken;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ExpiresInToken;

    /**
     * @ORM\Column(type="bigint")
     */
    private $cuitCuil;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tgdPersonaId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Programa", mappedBy="user")
     */
    private $programas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Noticia", mappedBy="user")
     */
    private $noticias;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Agenda", mappedBy="user")
     */
    private $agendas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ComentarioDebate", mappedBy="user")
     */
    private $comentarioDebates;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Debate", mappedBy="user")
     */
    private $debates;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ong", inversedBy="colaborators", cascade={"persist"})
     */
    private $ongColaborator;

    public function __construct()
    {
        parent::__construct();
        $this->programas = new ArrayCollection();
        $this->noticias = new ArrayCollection();
        $this->agendas = new ArrayCollection();
        $this->comentarioDebates = new ArrayCollection();
        $this->dabates = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->cuitCuil." (".$this->email.")";
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getOng(): ?Ong
    {
        return $this->ong;
    }

    public function setOng(?Ong $ong): self
    {
        $this->ong = $ong;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $ong === null ? null : $this;
        if ($newUser !== $ong->getUser()) {
            $ong->setUser($newUser);
        }

        return $this;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?string $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    public function getExpiresInToken(): ?int
    {
        return $this->ExpiresInToken;
    }

    public function setExpiresInToken(?int $ExpiresInToken): self
    {
        $this->ExpiresInToken = $ExpiresInToken;

        return $this;
    }

    public function getCuitCuil(): ?string
    {
        return $this->cuitCuil;
    }

    public function setCuitCuil(string $cuitCuil): self
    {
        $this->cuitCuil = $cuitCuil;
        //Set username by cuitCuil
        $this->setUsername($cuitCuil);

        return $this;
    }

    public function getTgdPersonaId(): ?int
    {
        return $this->tgdPersonaId;
    }

    public function setTgdPersonaId(?int $tgdPersonaId): self
    {
        $this->tgdPersonaId = $tgdPersonaId;

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
            $programa->setUser($this);
        }

        return $this;
    }

    public function removePrograma(Programa $programa): self
    {
        if ($this->programas->contains($programa)) {
            $this->programas->removeElement($programa);
            // set the owning side to null (unless already changed)
            if ($programa->getUser() === $this) {
                $programa->setUser(null);
            }
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
            $noticia->setUser($this);
        }

        return $this;
    }

    public function removeNoticia(Noticia $noticia): self
    {
        if ($this->noticias->contains($noticia)) {
            $this->noticias->removeElement($noticia);
            // set the owning side to null (unless already changed)
            if ($noticia->getUser() === $this) {
                $noticia->setUser(null);
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
            $agenda->setUser($this);
        }

        return $this;
    }

    public function removeAgenda(Agenda $agenda): self
    {
        if ($this->agendas->contains($agenda)) {
            $this->agendas->removeElement($agenda);
            // set the owning side to null (unless already changed)
            if ($agenda->getUser() === $this) {
                $agenda->setUser(null);
            }
        }

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
            $comentarioDebate->setUser($this);
        }

        return $this;
    }

    public function removeComentarioDebate(ComentarioDebate $comentarioDebate): self
    {
        if ($this->comentarioDebates->contains($comentarioDebate)) {
            $this->comentarioDebates->removeElement($comentarioDebate);
            // set the owning side to null (unless already changed)
            if ($comentarioDebate->getUser() === $this) {
                $comentarioDebate->setUser(null);
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
            $debate->setUser($this);
        }

        return $this;
    }

    public function removeDebate(Debate $debate): self
    {
        if ($this->debates->contains($debate)) {
            $this->debates->removeElement($debate);
            // set the owning side to null (unless already changed)
            if ($debate->getUser() === $this) {
                $debate->setUser(null);
            }
        }
        return $this;
    }

    public function getOngColaborator(): ?Ong
    {
        return $this->ongColaborator;
    }

    public function setOngColaborator(?Ong $ongColaborator): self
    {
        $this->ongColaborator = $ongColaborator;

        return $this;
    }
}
