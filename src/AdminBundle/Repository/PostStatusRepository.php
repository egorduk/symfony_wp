<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\PostStatus;
use AdminBundle\Entity\PostType;
use Doctrine\ORM\EntityRepository;

class PostStatusRepository extends EntityRepository
{
    public function save(PostStatus $object, $flush = false)
    {
        $this->getEntityManager()->persist($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return $object;
    }

    public function remove(PostStatus $object, $flush = false)
    {
        $this->getEntityManager()->remove($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return true;
    }
}
