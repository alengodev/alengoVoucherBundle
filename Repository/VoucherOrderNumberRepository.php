<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Repository;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrderNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method VoucherOrderNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherOrderNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherOrderNumber[] findAll()
 * @method VoucherOrderNumber[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherOrderNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VoucherOrderNumber::class);
    }

    public function save()
    {
        $qb = $this->find(1);

        if (!$qb instanceof VoucherOrderNumber) {
            throw new NotFoundHttpException(
                'No order number found',
            );
        }

        $qb->setConsecutiveNumber($qb->getConsecutiveNumber() + 1);

        $this->getEntityManager()->persist($qb);
        $this->getEntityManager()->flush();

        return $qb->getConsecutiveNumber();
    }

    // /**
    //  * @return VoucherOrderNumber[] Returns an array of VoucherOrderNumber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VoucherOrderNumber
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
