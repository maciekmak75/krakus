<?php

namespace OcenyBundle\Repository;

/**
 * EmailRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EmailRepository extends \Doctrine\ORM\EntityRepository {

    public function findEmail2Send() {
        $data = $this->createQueryBuilder('em')
                        ->where('em.czyWyslany = :sent')
                        ->setParameter('sent', FALSE)
                        ->andWhere('em.dataPowiadomienia <= :notificationDate')
                        ->setParameter('notificationDate', new \DateTime("now"))
                        ->getQuery()->getResult();

        return $data;
    }

}