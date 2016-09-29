<?php

namespace Dywee\CMSBundle\Repository;

use Doctrine\ORM\EntityRepository;



class CustomFormRepository extends EntityRepository
{
    public function findForJson()
    {
        $qb = $this->createQueryBuilder('m')
            ->select('m.id, m.name')
        ;

        $query = $qb->getQuery();

        return $query->getArrayResult();
    }
}
