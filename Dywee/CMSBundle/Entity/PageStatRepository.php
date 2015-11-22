<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageStatRepository extends EntityRepository
{
    public function findLastStatsForPage($page, $detail = 'daily'){

        $date = new \DateTime("previous week");
        $date = $date->format('Y/m/d 00:00:00');

        $qb = $this->createQueryBuilder('s')
            ->select('count(s) as vues, DATE(s.createdAt) as createdAt')
            ->where('s.page = :page and s.createdAt >= :date')
            ->setParameters(array('page' => $page, 'date' => $date))
            ->orderBy('s.createdAt', 'asc');

        if($detail == 'daily')
            $qb->groupBy('createdAt');

        return $qb->getQuery()->getResult();
    }
}
