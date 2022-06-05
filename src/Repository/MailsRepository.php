<?php

namespace App\Repository;

use App\Entity\Mails;
use App\Entity\Messages;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Mails>
 *
 * @method Mails|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mails|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mails[]    findAll()
 * @method Mails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
// Mondestin
class MailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mails::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Mails $entity, bool $flush = true): void
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
    public function remove(Mails $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Mails[] Returns an array of Mails objects
     */

    // get all emails received from the user
    public function getCurentUserMailsRe($email)
    {
        $placeholder = "important";
        return $this->createQueryBuilder('m')
            ->andWhere('m.send_to = :val')
            ->andWhere('m.placeholder = :pla')
            ->setParameter('val', $email)
            ->setParameter('pla', $placeholder)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // get all emails send from the user
    public function getCurentUserMailsSe($email)
    {
        $placeholder = "important";
        return $this->createQueryBuilder('m')
            ->andWhere('m.send_from = :val')
            ->andWhere('m.placeholder = :pla')
            ->setParameter('val', $email)
            ->setParameter('pla', $placeholder)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    // get all emails archived by the user
    public function getCurentUserMailsArchives($email)
    {
        $placeholder = "archived";
        return $this->createQueryBuilder('m')
            ->andWhere('m.send_to = :val')
            ->andWhere('m.placeholder = :pla')
            ->setParameter('val', $email)
            ->setParameter('pla', $placeholder)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
    // get all emails corbeille by the user
    public function getCurentUserMailsCorbeille($email)
    {
        $corbeille = "corbeille";
        return $this->createQueryBuilder('m')
            ->andWhere('m.send_to = :val')
            ->andWhere('m.placeholder = :pla')
            ->setParameter('val', $email)
            ->setParameter('pla', $corbeille)
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    // get the information of the mail
    public function readThisMail($mail)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.id = :mail')
            ->setParameter('mail', $mail)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
