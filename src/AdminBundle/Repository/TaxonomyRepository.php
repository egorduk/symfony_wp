<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Taxonomy;
use Doctrine\ORM\EntityRepository;

class TaxonomyRepository extends EntityRepository
{
    public function save(Taxonomy $object, $flush = false)
    {
        $this->getEntityManager()->persist($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return $object;
    }

    public function remove(Taxonomy $object, $flush = false)
    {
        $this->getEntityManager()->remove($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return true;
    }
}
