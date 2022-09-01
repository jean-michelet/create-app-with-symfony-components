<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function findPostsWithCommentsAndAuthors()
    {
        $dql = "SELECT p FROM App\Entity\Post p 
            JOIN p.comments c 
            JOIN c.author u
        ";

        return $this->getEntityManager()
            ->createQuery($dql)
            ->getResult();
    }
}
