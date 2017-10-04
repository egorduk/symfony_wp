<?php

namespace AdminBundle\Repository;

use AdminBundle\Entity\Post;
use AdminBundle\Entity\PostStatus;
use AdminBundle\Entity\PostType;
use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function save(Post $object, $flush = true)
    {
        $this->getEntityManager()->persist($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return $object;
    }

    public function remove(Post $object, $flush = true)
    {
        $this->getEntityManager()->remove($object);

        if ($flush === true) {
            $this->getEntityManager()->flush();
        }

        return true;
    }

    public function getPublishPosts()
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from(Post::class, 'p')
            ->join('p.postStatus', 'ps')
            ->where('ps.code = :publish')
            ->setParameter('publish', 'p')
            ->getQuery()
            ->getResult()
        ;
    }
}
