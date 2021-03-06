<?php

namespace OcenyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="OcenyBundle\Repository\EmailRepository")
 */
class Email {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var array<string>
     *
     * @ORM\Column(name="Adres", type="simple_array", length=255)
     */
    private $adres;

    /**
     * @var string
     *
     * @ORM\Column(name="Temat", type="string", length=255)
     */
    private $temat;

    /**
     * @var string
     *
     * @ORM\Column(name="Wiadomosc", type="string", length=255)
     */
    private $wiadomosc;

    /**
     * @var date
     *
     * @ORM\Column(name="DataPowiadomienia", type="date", nullable=true)
     */
    private $dataPowiadomienia;

    /**
     * @var date
     *
     * @ORM\Column(name="DataWyslania", type="date", nullable=true)
     */
    private $dataWyslania = NULL;

    /**
     * @var bool
     *
     * @ORM\Column(name="CzyWyslany", type="boolean")
     */
    private $czyWyslany = FALSE;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set temat
     *
     * @param string $temat
     *
     * @return Email
     */
    public function setTemat($temat) {
        $this->temat = $temat;

        return $this;
    }

    /**
     * Get temat
     *
     * @return string
     */
    public function getTemat() {
        return $this->temat;
    }

    /**
     * Set wiadomosc
     *
     * @param string $wiadomosc
     *
     * @return Email
     */
    public function setWiadomosc($wiadomosc) {
        $this->wiadomosc = $wiadomosc;

        return $this;
    }

    /**
     * Get wiadomosc
     *
     * @return string
     */
    public function getWiadomosc() {
        return $this->wiadomosc;
    }

    /**
     * Set czyWyslany
     *
     * @param boolean $czyWyslany
     *
     * @return Email
     */
    public function setCzyWyslany($czyWyslany) {
        $this->czyWyslany = $czyWyslany;

        return $this;
    }

    /**
     * Get czyWyslany
     *
     * @return boolean
     */
    public function getCzyWyslany() {
        return $this->czyWyslany;
    }

    /**
     * Set dataPowiadomienia
     *
     * @param \DateTime $dataPowiadomienia
     *
     * @return Email
     */
    public function setDataPowiadomienia($dataPowiadomienia) {
        $this->dataPowiadomienia = $dataPowiadomienia;

        return $this;
    }

    /**
     * Get dataPowiadomienia
     *
     * @return \DateTime
     */
    public function getDataPowiadomienia() {
        return $this->dataPowiadomienia;
    }

    /**
     * Set dataWyslania
     *
     * @param \DateTime $dataWyslania
     *
     * @return Email
     */
    public function setDataWyslania($dataWyslania) {
        $this->dataWyslania = $dataWyslania;

        return $this;
    }

    /**
     * Get dataWyslania
     *
     * @return \DateTime
     */
    public function getDataWyslania() {
        return $this->dataWyslania;
    }

    /**
     * Set adres
     *
     * @param array $adres
     *
     * @return Email
     */
    public function setAdres($adres) {
        $this->adres = is_array($adres) ? $adres : array($adres);

        return $this;
    }

    /**
     * Get adres
     *
     * @return array
     */
    public function getAdres() {
        return $this->adres;
    }

}
