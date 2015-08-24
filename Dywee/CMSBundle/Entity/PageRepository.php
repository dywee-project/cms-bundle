<?php

namespace Dywee\CMSBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends EntityRepository
{
    public function findHomePage()
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.idSite = :idSite and p.type = 1')
            ->setParameters(array('idSite' => 1))
            ;

        $query = $queryBuilder->getQuery();

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $return = $query->getArrayResult();
        if(count($return) == 1)
            return $return[0];
        else return -1;
    }

    public function getMenu()
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->select('p')
            ->leftJoin('DyweeCMSBundle:Page', 'pc', 'with', 'pc.parent = p.id')
            ->where('p.inMenu = 1 and p.parent is null')
            ->orderBy('p.menuOrder', 'asc');

        $query = $queryBuilder->getQuery();

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        return $query->getResult();
    }

    public function findBySeoUrl($url)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.seoUrl = :seoUrl')
            ->setParameter('seoUrl', $url);

        $query = $qb->getQuery();

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $result = $query->getArrayResult();

        return $result[0];
    }

    public function findById($id)
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        $query = $qb->getQuery();

        $query->setHint(
            \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
            'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
        );

        $result = $query->getArrayResult();

        return $result[0];
    }
}