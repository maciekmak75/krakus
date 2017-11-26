<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ankieta
 *
 * @ORM\Table(name="ankieta")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\AnkietaRepository")
 */
class Ankieta
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="MozliweOdpowiedzi", mappedBy="idAnkieta")
     * @ORM\OneToMany(targetEntity="Odpowiedzi", mappedBy="idAnkieta")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="czyPokazywac", type="boolean")
     */
    private $czyPokazywac;

    /**
     * @var bool
     *
     * @ORM\Column(name="typ", type="boolean")
     */
    private $typ;

    /**
     * @var int
     *
     * @ORM\Column(name="Sekcja", type="integer")
     */
    private $sekcja;

    /**
     * @var int
     *
     * @ORM\Column(name="Status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="Pytanie", type="string", length=1024, nullable=true)
     */
    private $pytanie;


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
     * Set czyPokazywac
     *
     * @param boolean $czyPokazywac
     *
     * @return Ankieta
     */
    public function setCzyPokazywac($czyPokazywac)
    {
        $this->czyPokazywac = $czyPokazywac;

        return $this;
    }

    /**
     * Get czyPokazywac
     *
     * @return boolean
     */
    public function getCzyPokazywac()
    {
        return $this->czyPokazywac;
    }

    /**
     * Set typ
     *
     * @param boolean $typ
     *
     * @return Ankieta
     */
    public function setTyp($typ)
    {
        $this->typ = $typ;

        return $this;
    }

    /**
     * Get typ
     *
     * @return boolean
     */
    public function getTyp()
    {
        return $this->typ;
    }

    /**
     * Set sekcja
     *
     * @param integer $sekcja
     *
     * @return Ankieta
     */
    public function setSekcja($sekcja)
    {
        $this->sekcja = $sekcja;

        return $this;
    }

    /**
     * Get sekcja
     *
     * @return integer
     */
    public function getSekcja()
    {
        return $this->sekcja;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Ankieta
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

    /**
     * Set pytanie
     *
     * @param string $pytanie
     *
     * @return Ankieta
     */
    public function setPytanie($pytanie)
    {
        $this->pytanie = $pytanie;

        return $this;
    }

    /**
     * Get pytanie
     *
     * @return string
     */
    public function getPytanie()
    {
        return $this->pytanie;
    }
}
