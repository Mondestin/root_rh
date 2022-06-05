<?php

namespace App\Repository;

use App\Entity\Items;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Items>
 *
 * @method Items|null find($id, $lockMode = null, $lockVersion = null)
 * @method Items|null findOneBy(array $criteria, array $orderBy = null)
 * @method Items[]    findAll()
 * @method Items[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Items::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Items $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Items $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Items[] Returns an array of Items objects
     */

    //  get all items beloging to the task id
    public function getLinkedItems($task_id)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.task_id = :val')
            ->setParameter('val', $task_id)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Items[] Returns an array of Items objects
     */

    //  get all items beloging to the task id
    public function getLinkedItemsDone($task_id)
    {
        $status = "Terminé";
        return $this->createQueryBuilder('i')
            ->andWhere('i.task_id = :val')
            ->andWhere('i.status = :status')
            ->setParameter('val', $task_id)
            ->setParameter('status', $status)
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Items
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
