<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sala
 *
 * @ORM\Table(name="sala")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SalaRepository")
 */
class Sala
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
     * @var string
     *
     * @ORM\Column(name="ubicacion_piso", type="string", length=255)
     */
    private $ubicacionPiso;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="smallint")
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_inicio", type="time")
     */
    private $horaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_fin", type="time")
     */
    private $horaFin;

    /**
     * @var bool
     *
     * @ORM\Column(name="flag_activa", type="boolean")
     */
    private $flagActiva;

    /**
    * @ORM\OneToMany(targetEntity="ApartadoSala", mappedBy="sala")
    */
    private $reservaciones;


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
     * @return Sala
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
     * Get nombre
     *
     * @return string
     */
    public function __toString()
    {
        return $this->nombre;
    }

    /**
     * Set ubicacionPiso
     *
     * @param string $ubicacionPiso
     *
     * @return Sala
     */
    public function setUbicacionPiso($ubicacionPiso)
    {
        $this->ubicacionPiso = $ubicacionPiso;

        return $this;
    }

    /**
     * Get ubicacionPiso
     *
     * @return string
     */
    public function getUbicacionPiso()
    {
        return $this->ubicacionPiso;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return Sala
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set flagActiva
     *
     * @param boolean $flagActiva
     *
     * @return Sala
     */
    public function setFlagActiva($flagActiva)
    {
        $this->flagActiva = $flagActiva;

        return $this;
    }

    /**
     * Get flagActiva
     *
     * @return bool
     */
    public function getFlagActiva()
    {
        return $this->flagActiva;
    }

    public function __construct()
    {
        $this->flagActiva = 1;
    }

    /**
     * Add reservacione
     *
     * @param \AppBundle\Entity\ApartadoSala $reservacione
     *
     * @return Sala
     */
    public function addReservacione(\AppBundle\Entity\ApartadoSala $reservacione)
    {
        $this->reservaciones[] = $reservacione;

        return $this;
    }

    /**
     * Remove reservacione
     *
     * @param \AppBundle\Entity\ApartadoSala $reservacione
     */
    public function removeReservacione(\AppBundle\Entity\ApartadoSala $reservacione)
    {
        $this->reservaciones->removeElement($reservacione);
    }

    /**
     * Get reservaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReservaciones()
    {
        return $this->reservaciones;
    }

    /**
     * Set horaInicio
     *
     * @param \DateTime $horaInicio
     *
     * @return Sala
     */
    public function setHoraInicio($horaInicio)
    {
        $this->horaInicio = $horaInicio;

        return $this;
    }

    /**
     * Get horaInicio
     *
     * @return \DateTime
     */
    public function getHoraInicio()
    {
        return $this->horaInicio;
    }

    /**
     * Set horaFin
     *
     * @param \DateTime $horaFin
     *
     * @return Sala
     */
    public function setHoraFin($horaFin)
    {
        $this->horaFin = $horaFin;

        return $this;
    }

    /**
     * Get horaFin
     *
     * @return \DateTime
     */
    public function getHoraFin()
    {
        return $this->horaFin;
    }
}
