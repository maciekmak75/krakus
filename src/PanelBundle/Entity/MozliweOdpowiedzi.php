<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MozliweOdpowiedzi
 *
 * @ORM\Table(name="mozliwe_odpowiedzi")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\MozliweOdpowiedziRepository")
 */
class MozliweOdpowiedzi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Odpowiedzi", mappedBy="idOdpowiedz")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Ankieta", inversedBy="id")
     */
    private $idAnkieta;

    /**
     * @var string
     *
     * @ORM\Column(name="Nazwa", type="string", length=1024)
     */
    private $nazwa;


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
     * Set nazwa
     *
     * @param string $nazwa
     *
     * @return MozliweOdpowiedzi
     */
    public function setNazwa($nazwa)
    {
        $this->nazwa = $nazwa;

        return $this;
    }

    /**
     * Get nazwa
     *
     * @return string
     */
    public function getNazwa()
    {
        return $this->nazwa;
    }

    /**
     * Set idPytanie
     *
     * @param \PanelBundle\Entity\Pytanie $idPytanie
     *
     * @return MozliweOdpowiedzi
     */
    public function setIdPytanie(\PanelBundle\Entity\Pytanie $idPytanie = null)
    {
        $this->idPytanie = $idPytanie;

        return $this;
    }

    /**
     * Get idPytanie
     *
     * @return \PanelBundle\Entity\Pytanie
     */
    public function getIdPytanie()
    {
        return $this->idPytanie;
    }

    /**
     * Set idAnkieta
     *
     * @param \PanelBundle\Entity\Ankieta $idAnkieta
     *
     * @return MozliweOdpowiedzi
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
