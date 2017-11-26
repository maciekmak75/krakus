<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pytanie
 *
 * @ORM\Table(name="pytanie")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\PytanieRepository")
 */
class Pytanie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="MozliweOdpowiedzi", mappedBy="idPytanie")
     */
    private $id;

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
     * @return Pytanie
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
}
