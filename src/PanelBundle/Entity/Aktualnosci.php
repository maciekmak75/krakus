<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aktualnosci
 *
 * @ORM\Table(name="aktualnosci")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\AktualnosciRepository")
 */
class Aktualnosci
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
     * @ORM\ManyToOne(targetEntity="Osoby", inversedBy="id")
     */
    private $idUzytkownik;

    /**
     * @var string
     *
     * @ORM\Column(name="Tytul", type="string", length=255)
     */
    private $tytul;

    /**
     * @var string
     *
     * @ORM\Column(name="Tresc", type="string", length=4095)
     */
    private $tresc;

    /**
     * @var date
     *
     * @ORM\Column(name="DataOd", type="date")

     */
    private $dataOd;

    /**
     * @var date
     *
     * @ORM\Column(name="DataDo", type="date")

     */
    private $dataDo;

    /**
     * @var datetime
     *
     * @ORM\Column(name="DataGeneracji", type="datetime")

     */
    private $dataGeneracji;

    /**
     * @var int
     *
     * @ORM\Column(name="Status", type="integer")
     */
    private $status;


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
     * @return Aktualnosci
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
     * Set tytul
     *
     * @param string $tytul
     *
     * @return Aktualnosci
     */
    public function setTytul($tytul)
    {
        $this->tytul = $tytul;

        return $this;
    }

    /**
     * Get tytul
     *
     * @return string
     */
    public function getTytul()
    {
        return $this->tytul;
    }

    /**
     * Set tresc
     *
     * @param string $tresc
     *
     * @return Aktualnosci
     */
    public function setTresc($tresc)
    {
        $this->tresc = $tresc;

        return $this;
    }

    /**
     * Get tresc
     *
     * @return string
     */
    public function getTresc()
    {
        return $this->tresc;
    }

    /**
     * Set dataOd
     *
     * @param \DateTime $dataOd
     *
     * @return Aktualnosci
     */
    public function setDataOd($dataOd)
    {
        $this->dataOd = $dataOd;

        return $this;
    }

    /**
     * Get dataOd
     *
     * @return \DateTime
     */
    public function getDataOd()
    {
        return $this->dataOd;
    }

    /**
     * Set dataDo
     *
     * @param \DateTime $dataDo
     *
     * @return Aktualnosci
     */
    public function setDataDo($dataDo)
    {
        $this->dataDo = $dataDo;

        return $this;
    }

    /**
     * Get dataDo
     *
     * @return \DateTime
     */
    public function getDataDo()
    {
        return $this->dataDo;
    }

    /**
     * Set dataGeneracji
     *
     * @param \DateTime $dataGeneracji
     *
     * @return Aktualnosci
     */
    public function setDataGeneracji($dataGeneracji)
    {
        $this->dataGeneracji = $dataGeneracji;

        return $this;
    }

    /**
     * Get dataGeneracji
     *
     * @return \DateTime
     */
    public function getDataGeneracji()
    {
        return $this->dataGeneracji;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Aktualnosci
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
}
