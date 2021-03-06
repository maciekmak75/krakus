<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * imieNazwisko
 *
 * @ORM\Table(name="imie_nazwisko")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\imieNazwiskoRepository")
 */
class imieNazwisko
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
     * @ORM\Column(name="Nazwa", type="string", length=255)
     */
    private $nazwa;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Osoby", inversedBy="id")
     */
    private $idUzytkownik;


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
     * @return imieNazwisko
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
     * Set idUzytkownik
     *
     * @param \PanelBundle\Entity\Osoby $idUzytkownik
     *
     * @return imieNazwisko
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
}
