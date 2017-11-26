<?php

namespace PanelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Osoby
 *
 * @ORM\Table(name="osoby")
 * @ORM\Entity(repositoryClass="PanelBundle\Repository\OsobyRepository")
 */
class Osoby implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\OneToMany(targetEntity="Aktualnosci", mappedBy="idUzytkownik")
     * @ORM\OneToMany(targetEntity="Odpowiedzi", mappedBy="idUzytkownik")
     * @ORM\OneToMany(targetEntity="imieNazwisko", mappedBy="idUzytkownik")
     *      */
    private $id;

    /**
     * @var string
     *
     *
     * @ORM\Column(name="Login", type="string", length=255, unique=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="Haslo", type="string", length=255, unique=false, nullable=true)
     */
    private $haslo;

    /**
     * @var string
     *
     * @ORM\Column(name="Imie", type="string", length=255, unique=false, nullable=true)
     */
    private $imie;

    /**
     * @var string
     *
     * @ORM\Column(name="Nazwisko", type="string", length=255, unique=false)
     */
    private $nazwisko;

    /**
     * @var bool
     *
     * @ORM\Column(name="Plec", type="boolean")
     */
    private $plec;

    /**
     * @var date
     *
     * @ORM\Column(name="DataUrodzenia", type="date", nullable=true)

     */
    private $dataUrodzenia;

    /**
     * @var string
     *
     * @ORM\Column(name="MiejsceUrodzenia", type="string", length=255, nullable=true)
     */
    private $miejsceUrodzenia;

    /**
     * @var string
     *
     * @ORM\Column(name="ImieOjca", type="string", length=255, nullable=true)
     */
    private $imieOjca;

    /**
     * @var string
     *
     * @ORM\Column(name="ImieMatki", type="string", length=255, nullable=true)
     */
    private $imieMatki;

    /**
     * @var string
     *
     * @ORM\Column(name="NrDowodu", type="string", length=255, nullable=true)
     */
    private $nrDowodu;

    /**
     * @var string
     *
     * @ORM\Column(name="Pesel", type="string", length=255, nullable=true)
     */
    private $pesel;

    /**
     * @var string
     *
     * @ORM\Column(name="NrLegitymacji", type="string", length=255, nullable=true)
     */
    private $nrLegitymacji;

    /**
     * @var string
     *
     * @ORM\Column(name="NrPaszportu", type="string", length=255, nullable=true)
     */
    private $nrPaszportu;

    /**
     * @var date
     *
     * @ORM\Column(name="DataPaszport", type="date", nullable=true)

     */
    private $dataPaszport;

    /**
     * @var string
     *
     * @ORM\Column(name="Uczelnia", type="string", length=255, nullable=true)
     */
    private $uczelnia;

    /**
     * @var string
     *
     * @ORM\Column(name="Wydzial", type="string", length=255, nullable=true)
     */
    private $wydzial;

    /**
     * @var int
     *
     * @ORM\Column(name="Rok", type="integer", nullable=true)
     */
    private $rok;

    /**
     * @var string
     *
     * @ORM\Column(name="Adres", type="string", length=255, nullable=true)
     */
    private $adres;

    /**
     * @var string
     *
     * @ORM\Column(name="Zdjecie", type="string", length=255, nullable=true)
     */
    private $zdjecie;

    /**
     * @var string
     *
     * @ORM\Column(name="Wzrost", type="string", length=255, nullable=true)
     */
    private $wzrost;

    /**
     * @var int
     *
     * @ORM\Column(name="Sekcja", type="integer", nullable=true)
     */
    private $sekcja;

    /**
     * @var int
     *
     * @ORM\Column(name="Glos", type="integer", nullable=true)
     */
    private $glos;

    /**
     * @var int
     *
     * @ORM\Column(name="Status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var datetime
     *
     * @ORM\Column(name="DataWstapienia", type="datetime", nullable=true)

     */
    private $dataWstapienia;

    /**
     * @var datetime
     *
     * @ORM\Column(name="DataWystapienia", type="datetime", nullable=true)

     */
    private $dataWystapienia;

    /**
     * @var string
     *
     * @ORM\Column(name="Opis", type="string", length=4096, nullable=true)
     */
    private $opis;

    /**
     * @var string
     *
     * @ORM\Column(name="Telefon", type="string", length=255, nullable=true)
     */
    private $telefon;

    /**
     * @var string
     *
     * @ORM\Column(name="Mail", type="string", length=255, nullable=true)
     */
    private $mail;

    /**
     * @var bool
     *
     * @ORM\Column(name="Powiadomienia", type="boolean", nullable=true)
     */
    private $powiadomienia;


    /**
     * @var array<string>
     *
     * @ORM\Column(name="Role", type="simple_array", nullable=true)
     */
    private $role;

    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function getPassword() {
        return $this->haslo;
    }

    public function getRoles() {
        return $this->role;
        //return array('ROLE_ADMIN');
    }

    public function getUsername() {
        return $this->login;
    }

    public function eraseCredentials() {

    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->login,
            $this->haslo,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->login,
            $this->haslo,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function __toString()
    {
        return $this->imie.' '.$this->nazwisko;
    }

    public function _toString()
    {
        return $this->nazwisko.' '.$this->imie;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Osoby
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set haslo
     *
     * @param string $haslo
     *
     * @return Osoby
     */
    public function setHaslo($haslo)
    {
        $this->haslo = $haslo;

        return $this;
    }

    /**
     * Get haslo
     *
     * @return string
     */
    public function getHaslo()
    {
        return $this->haslo;
    }

    /**
     * Set role
     *
     * @param array $role
     *
     * @return Osoby
     */
    public function setRole($role)
    {
        $this->role = array_unique($role);

        return $this;
    }

    public function addRole($rola) {
        array_push($this->role, strtoupper($rola));
        $this->role = array_unique($this->role);
        return $this;
    }

    public function removeRole($rola) {
        if (in_array(strtoupper($rola), $this->role)) {
            $tmp = array_diff($this->role, array(strtoupper($rola)));
            $this->role = array_unique($tmp);
        }
        return $this;
    }

    public function hasRole($rola) {
        if (in_array(strtoupper($rola), $this->role)) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Get role
     *
     * @return array
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set imie
     *
     * @param string $imie
     *
     * @return Osoby
     */
    public function setImie($imie)
    {
        $this->imie = $imie;

        return $this;
    }

    /**
     * Get imie
     *
     * @return string
     */
    public function getImie()
    {
        return $this->imie;
    }

    /**
     * Set nazwisko
     *
     * @param string $nazwisko
     *
     * @return Osoby
     */
    public function setNazwisko($nazwisko)
    {
        $this->nazwisko = $nazwisko;

        return $this;
    }

    /**
     * Get nazwisko
     *
     * @return string
     */
    public function getNazwisko()
    {
        return $this->nazwisko;
    }

    /**
     * Set plec
     *
     * @param boolean $plec
     *
     * @return Osoby
     */
    public function setPlec($plec)
    {
        $this->plec = $plec;

        return $this;
    }

    /**
     * Get plec
     *
     * @return boolean
     */
    public function getPlec()
    {
        return $this->plec;
    }

    /**
     * Set dataUrodzenia
     *
     * @param \DateTime $dataUrodzenia
     *
     * @return Osoby
     */
    public function setDataUrodzenia($dataUrodzenia)
    {
        $this->dataUrodzenia = $dataUrodzenia;

        return $this;
    }

    /**
     * Get dataUrodzenia
     *
     * @return \DateTime
     */
    public function getDataUrodzenia()
    {
        return $this->dataUrodzenia;
    }

    /**
     * Set miejsceUrodzenia
     *
     * @param string $miejsceUrodzenia
     *
     * @return Osoby
     */
    public function setMiejsceUrodzenia($miejsceUrodzenia)
    {
        $this->miejsceUrodzenia = $miejsceUrodzenia;

        return $this;
    }

    /**
     * Get miejsceUrodzenia
     *
     * @return string
     */
    public function getMiejsceUrodzenia()
    {
        return $this->miejsceUrodzenia;
    }

    /**
     * Set imieOjca
     *
     * @param string $imieOjca
     *
     * @return Osoby
     */
    public function setImieOjca($imieOjca)
    {
        $this->imieOjca = $imieOjca;

        return $this;
    }

    /**
     * Get imieOjca
     *
     * @return string
     */
    public function getImieOjca()
    {
        return $this->imieOjca;
    }

    /**
     * Set imieMatki
     *
     * @param string $imieMatki
     *
     * @return Osoby
     */
    public function setImieMatki($imieMatki)
    {
        $this->imieMatki = $imieMatki;

        return $this;
    }

    /**
     * Get imieMatki
     *
     * @return string
     */
    public function getImieMatki()
    {
        return $this->imieMatki;
    }

    /**
     * Set nrDowodu
     *
     * @param string $nrDowodu
     *
     * @return Osoby
     */
    public function setNrDowodu($nrDowodu)
    {
        $this->nrDowodu = $nrDowodu;

        return $this;
    }

    /**
     * Get nrDowodu
     *
     * @return string
     */
    public function getNrDowodu()
    {
        return $this->nrDowodu;
    }

    /**
     * Set pesel
     *
     * @param string $pesel
     *
     * @return Osoby
     */
    public function setPesel($pesel)
    {
        $this->pesel = $pesel;

        return $this;
    }

    /**
     * Get pesel
     *
     * @return string
     */
    public function getPesel()
    {
        return $this->pesel;
    }

    /**
     * Set nrLegitymacji
     *
     * @param string $nrLegitymacji
     *
     * @return Osoby
     */
    public function setNrLegitymacji($nrLegitymacji)
    {
        $this->nrLegitymacji = $nrLegitymacji;

        return $this;
    }

    /**
     * Get nrLegitymacji
     *
     * @return string
     */
    public function getNrLegitymacji()
    {
        return $this->nrLegitymacji;
    }

    /**
     * Set nrPaszportu
     *
     * @param string $nrPaszportu
     *
     * @return Osoby
     */
    public function setNrPaszportu($nrPaszportu)
    {
        $this->nrPaszportu = $nrPaszportu;

        return $this;
    }

    /**
     * Get nrPaszportu
     *
     * @return string
     */
    public function getNrPaszportu()
    {
        return $this->nrPaszportu;
    }

    /**
     * Set dataPaszport
     *
     * @param \DateTime $dataPaszport
     *
     * @return Osoby
     */
    public function setDataPaszport($dataPaszport)
    {
        $this->dataPaszport = $dataPaszport;

        return $this;
    }

    /**
     * Get dataPaszport
     *
     * @return \DateTime
     */
    public function getDataPaszport()
    {
        return $this->dataPaszport;
    }

    /**
     * Set uczelnia
     *
     * @param string $uczelnia
     *
     * @return Osoby
     */
    public function setUczelnia($uczelnia)
    {
        $this->uczelnia = $uczelnia;

        return $this;
    }

    /**
     * Get uczelnia
     *
     * @return string
     */
    public function getUczelnia()
    {
        return $this->uczelnia;
    }

    /**
     * Set wydzial
     *
     * @param string $wydzial
     *
     * @return Osoby
     */
    public function setWydzial($wydzial)
    {
        $this->wydzial = $wydzial;

        return $this;
    }

    /**
     * Get wydzial
     *
     * @return string
     */
    public function getWydzial()
    {
        return $this->wydzial;
    }

    /**
     * Set rok
     *
     * @param integer $rok
     *
     * @return Osoby
     */
    public function setRok($rok)
    {
        $this->rok = $rok;

        return $this;
    }

    /**
     * Get rok
     *
     * @return integer
     */
    public function getRok()
    {
        return $this->rok;
    }

    /**
     * Set adres
     *
     * @param string $adres
     *
     * @return Osoby
     */
    public function setAdres($adres)
    {
        $this->adres = $adres;

        return $this;
    }

    /**
     * Get adres
     *
     * @return string
     */
    public function getAdres()
    {
        return $this->adres;
    }

    /**
     * Set zdjecie
     *
     * @param string $zdjecie
     *
     * @return Osoby
     */
    public function setZdjecie($zdjecie)
    {
        $this->zdjecie = $zdjecie;

        return $this;
    }

    /**
     * Get zdjecie
     *
     * @return string
     */
    public function getZdjecie()
    {
        return $this->zdjecie;
    }

    /**
     * Set wzrost
     *
     * @param string $wzrost
     *
     * @return Osoby
     */
    public function setWzrost($wzrost)
    {
        $this->wzrost = $wzrost;

        return $this;
    }

    /**
     * Get wzrost
     *
     * @return string
     */
    public function getWzrost()
    {
        return $this->wzrost;
    }

    /**
     * Set sekcja
     *
     * @param integer $sekcja
     *
     * @return Osoby
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
     * Set glos
     *
     * @param integer $glos
     *
     * @return Osoby
     */
    public function setGlos($glos)
    {
        $this->glos = $glos;

        return $this;
    }

    /**
     * Get glos
     *
     * @return integer
     */
    public function getGlos()
    {
        return $this->glos;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Osoby
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
     * Set dataWstapienia
     *
     * @param \DateTime $dataWstapienia
     *
     * @return Osoby
     */
    public function setDataWstapienia($dataWstapienia)
    {
        $this->dataWstapienia = $dataWstapienia;

        return $this;
    }

    /**
     * Get dataWstapienia
     *
     * @return \DateTime
     */
    public function getDataWstapienia()
    {
        return $this->dataWstapienia;
    }

    /**
     * Set dataWystapienia
     *
     * @param \DateTime $dataWystapienia
     *
     * @return Osoby
     */
    public function setDataWystapienia($dataWystapienia)
    {
        $this->dataWystapienia = $dataWystapienia;

        return $this;
    }

    /**
     * Get dataWystapienia
     *
     * @return \DateTime
     */
    public function getDataWystapienia()
    {
        return $this->dataWystapienia;
    }

    /**
     * Set opis
     *
     * @param string $opis
     *
     * @return Osoby
     */
    public function setOpis($opis)
    {
        $this->opis = $opis;

        return $this;
    }

    /**
     * Get opis
     *
     * @return string
     */
    public function getOpis()
    {
        return $this->opis;
    }

    /**
     * Set telefon
     *
     * @param string $telefon
     *
     * @return Osoby
     */
    public function setTelefon($telefon)
    {
        $this->telefon = $telefon;

        return $this;
    }

    /**
     * Get telefon
     *
     * @return string
     */
    public function getTelefon()
    {
        return $this->telefon;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Osoby
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set powiadomienia
     *
     * @param boolean $powiadomienia
     *
     * @return Osoby
     */
    public function setPowiadomienia($powiadomienia)
    {
        $this->powiadomienia = $powiadomienia;

        return $this;
    }

    /**
     * Get powiadomienia
     *
     * @return boolean
     */
    public function getPowiadomienia()
    {
        return $this->powiadomienia;
    }
}
