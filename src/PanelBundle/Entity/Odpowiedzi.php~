<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Odpowiedzi
 *
 * @ORM\Table(name="odpowiedzi")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\OdpowiedziRepository")
 */
class Odpowiedzi
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
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Osoby", inversedBy="id")
     */
    private $idUzytkownik;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="MozliweOdpowiedzi", inversedBy="id")
     */
    private $idOdpowiedz;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Ankieta", inversedBy="id")
     */
    private $idAnkieta;

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
     * Set idUzytkownik
     *
     * @param \PanelBundle\Entity\Osoby $idUzytkownik
     *
     * @return Odpowiedzi
     */
    public function setIdUzytkownik(\PanelBundle\Entity\Osoby $idUzytkownik = null)
    {
        $this->idUzytkownik = $idUzytkownik;

        return $this;
    }

    /**
     * Get idUzytkownik
     *
     * @return \PanelBundle\Entity\Osoby
     */
    public function getIdUzytkownik()
    {
        return $this->idUzytkownik;
    }

    /**
     * Set idOdpowiedz
     *
     * @param \PanelBundle\Entity\MozliweOdpowiedz $idOdpowiedz
     *
     * @return Odpowiedzi
     */
    public function setIdOdpowiedz(\PanelBundle\Entity\MozliweOdpowiedzi $idOdpowiedz = null)
    {
        $this->idOdpowiedz = $idOdpowiedz;

        return $this;
    }

    /**
     * Get idOdpowiedz
     *
     * @return \PanelBundle\Entity\MozliweOdpowiedz
     */
    public function getIdOdpowiedz()
    {
        return $this->idOdpowiedz;
    }

    /**
     * Set idAnkieta
     *
     * @param \PanelBundle\Entity\Ankieta $idAnkieta
     *
     * @return Odpowiedzi
     */
    public function setIdAnkieta(\PanelBundle\Entity\Ankieta $idAnkieta = null)
    {
        $this->idAnkieta = $idAnkieta;

        return $this;
    }

    /**
     * Get idAnkieta
     *
     * @return \PanelBundle\Entity\Ankieta
     */
    public function getIdAnkieta()
    {
        return $this->idAnkieta;
    }
}
