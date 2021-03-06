<?php

namespace PanelBundle\Repository;

use PanelBundle\Constants\Status;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * AktualnosciRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AktualnosciRepository extends \Doctrine\ORM\EntityRepository
{
    public function findCurrentNews($date, $status, $mode = false)
    {
        $query = $this->createQueryBuilder('n')
            ->where('n.dataOd < :id')->setParameter('id', $date)
            ->andWhere('n.dataDo > :id')->setParameter('id', $date)
            ->orderBy('n.dataGeneracji', 'DESC')
            ->getQuery()->getResult();

        if ($mode) {
            $k = 0;
            $entities = [];
            for ($i = 0; $i < count($query); $i++) {
                if (Status::czyPokazywac($status, $query[$i]->getStatus())) {
                    $entities[$k] = $query[$i];
                    $k++;
                }
            }
            return $entities;
        }
        return $query;
    }

    public function findArchive($date)
    {
        $query = $this->createQueryBuilder('n')
            ->where('n.dataDo < :id')->setParameter('id', $date)
            ->orderBy('n.dataGeneracji', 'DESC')
            ->getQuery()->getResult();

        return $query;
    }
}
