<?php

namespace App\Repository;

use App\Entity\UserImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserImages>
 *
 * @method UserImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserImages[]    findAll()
 * @method UserImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserImages::class);
    }

    public function removeUserImages($removeUserImages) {
        $qb = $this->_em->createQuery("DELETE FROM App\Entity\UserImages AS ui WHERE ui.id IN (:removedUserImages)");
        $qb->setParameter(':removedUserImages', json_decode($removeUserImages), Connection::PARAM_STR_ARRAY);
        return $qb->execute();
    }
}
