<?php

namespace PanelBundle\Repository;

/**
 * MozliweOdpowiedziRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MozliweOdpowiedziRepository extends \Doctrine\ORM\EntityRepository
{
    public function findSelectAnswers($idAnkieta) {

        $query = $this->findBy(array('idAnkieta'=>$idAnkieta));


        $dane = [];
        for ($i = 0; $i < count($query); $i++) {
            $dane[$query[$i]->getNazwa()] = $query[$i]->getId();
        }
        return $dane;
    }
}
