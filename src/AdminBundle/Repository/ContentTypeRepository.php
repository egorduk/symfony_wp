<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\ContentType;
use Doctrine\ORM\EntityRepository;

class ContentTypeRepository extends EntityRepository
{
    public function save(ContentType $object, $flush = false)
    {
        $this->getEntityManager()->persist($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return $object;
    }

    public function remove(ContentType $object, $flush = false)
    {
        $this->getEntityManager()->remove($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return true;
    }
}
