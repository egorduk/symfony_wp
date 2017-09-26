<?php

namespace AdminBundle\Helper;

use AdminBundle\Entity\Taxonomy;
use Doctrine\ORM\EntityManager;

class TaxonomyHelper
{
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAllTaxonomies()
    {
        return $this->em
            ->createQueryBuilder()
            ->select('t.name, t.slug, t.id, (SELECT t1.name FROM ' . Taxonomy::class . ' t1 WHERE t1.id = t.parent GROUP BY t.id) parent_name')
            ->from(Taxonomy::class, 't')
            ->getQuery()
            ->getScalarResult();
    }
}