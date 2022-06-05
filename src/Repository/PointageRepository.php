<?php

namespace App\Repository;

use App\Entity\Pointage;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Pointage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pointage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pointage[]    findAll()
 * @method Pointage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */

//Mondestin
class PointageRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pointage::class);
    }

    /**
     * @return Pointage[] Returns an array of Pointage objects
     */

    public function getPointages($employee, $validate)
    {
        // dd($employee);
        //get employee pointages
        return $this->createQueryBuilder('p')
            ->andWhere('p.employee_id = :employee')
            ->andWhere('p.validate = :val')
            ->setParameter('employee', $employee)
            ->setParameter('val', $validate)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findAllUserChecks($id)
    {
        //get all current user employee pointages from the db
        return $this->createQueryBuilder('p')
            ->andWhere('p.employee_id = :val')
            ->setParameter('val', $id)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function checkTodayIn($uId, $pDate)
    {
        //check if today exist in the db
        return $this->createQueryBuilder('p')
            ->andWhere('p.employee_id = :val')
            ->andWhere('p.pointing_date = :pDate')
            ->setParameter('val', $uId)
            ->setParameter('pDate', $pDate)
            ->getQuery()
            ->getResult();
    }

    public function updateUserPointage($uId, $pDate, $endTime, $pStatus)
    {
        // update employee's check out time for today's date
        $query = $this->_em->createQueryBuilder();
        return $query->update(Pointage::class, 'p')
            ->set('p.end_time', ':endTime')
            ->set('p.status', ':pStatus')
            ->andWhere('p.employee_id = :eId')
            ->andWhere('p.pointing_date = :pDate')
            ->setParameter('eId', $uId)
            ->setParameter('pDate', $pDate)
            ->setParameter('endTime', $endTime)
            ->setParameter('pStatus', $pStatus)
            ->getQuery()
            ->execute();
    }

    public function gestionPointage($pId, $comments, $pStatus)
    {
        // the manager updating the pointage of the employee
        $query = $this->_em->createQueryBuilder();
        return $query->update(Pointage::class, 'p')
            ->set('p.comments', ':comments')
            ->set('p.validate', ':pStatus')
            ->andWhere('p.id = :pId')
            ->setParameter('pId', $pId)
            ->setParameter('comments', $comments)
            ->setParameter('pStatus', $pStatus)
            ->getQuery()
            ->execute();
    }
}
