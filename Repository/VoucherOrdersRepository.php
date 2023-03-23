<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Repository;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategories;
use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VoucherOrders|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherOrders|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherOrders[] findAll()
 * @method VoucherOrders[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherOrdersRepository extends ServiceEntityRepository
{
    /**
     * @var ManagerRegistry|mixed
     */
    public $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;

        parent::__construct($registry, VoucherOrders::class);
    }

    public function save($data)
    {
        $qb = new VoucherOrders();
        $qb->setOrderNumber((new VoucherOrderNumberRepository($this->registry))->save());
        $qb->setIdCategories($this->getEntityManager()->getRepository(VoucherCategories::class)->findOneBy(['id' => $data['voucher_categories_id']]));
        $qb->setVoucherAmount($data['amount']);
        $qb->setVoucherHeader($data['header']);
        $qb->setVoucherText($data['text']);
        $qb->setFirstName($data['vorname']);
        $qb->setLastName($data['nachname']);
        $qb->setStreet($data['strasse']);
        $qb->setZip($data['plz']);
        $qb->setCity($data['ort']);
        $qb->setEmail($data['email']);
        $qb->setPhone($data['telefon']);
        $qb->setpaymentResponse($data['payment_response']);
        $qb->setpaymentStatus($data['payment_status']);
        $qb->setCreated(new \DateTime());

        $orderCode = $qb->getOrderNumber() . '-' . $this->uniqueId();
        $qb->setGeneratedVoucher($orderCode . '.pdf');

        $this->getEntityManager()->persist($qb);
        $this->getEntityManager()->flush();

        $response = [];
        $response['order_number'] = $qb->getOrderNumber();
        $response['order_created'] = $qb->getCreated();
        $response['order_code'] = $orderCode;
        $response['order_file'] = $orderCode . '.pdf';

        return $response;
    }

    // /**
    //  * @return VoucherOrders[] Returns an array of VoucherOrders objects
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
    public function findOneBySomeField($value): ?VoucherOrders
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    private function uniqueId($l = 8): string
    {
        return \substr(\md5(\uniqid((string) \random_int(0, \mt_getrandmax()), true)), 0, $l);
    }
}
