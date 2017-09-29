<?php

namespace AdminBundle\Helper;

use AdminBundle\Entity\Post;
use Doctrine\ORM\EntityManager;

class PostHelper
{
    private $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getPostsByPostTypeId()
    {
        return $this->em
            ->createQueryBuilder()
            ->select('t.name, t.slug, t.id, IFNULL((SELECT t1.name FROM ' . Taxonomy::class . ' t1 WHERE t1.id = t.parent GROUP BY t.id), \'No\') parent_name')
            ->from(Taxonomy::class, 't')
            ->getQuery()
            ->getScalarResult();
    }
}