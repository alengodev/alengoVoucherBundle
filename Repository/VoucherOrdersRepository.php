<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Repository;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategories;
use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sulu\Bundle\MediaBundle\Entity\Media;

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

    public function saveSentSuccess($orderUuid, $sent = true)
    {
        $qb = $this->findOneBy([
            'orderUuid' => $orderUuid,
        ]);

        if ($qb instanceof VoucherOrders) {
            $qb->setVoucherSent($sent);
        }

        $this->add($qb, true);

        return $qb;
    }

    public function savePaymentSuccess($data, $orderUuid)
    {
        $qb = $this->findOneBy([
            'orderUuid' => $orderUuid,
        ]);

        if ($qb instanceof VoucherOrders) {
            $orderNumber = $qb->getOrderNumber() ?? (new VoucherOrderNumberRepository($this->registry))->save();
            $voucherCode = $qb->getVoucherCode() ?? $data['voucherCode'];
            $generatedVoucher = $voucherCode . '-' . $orderNumber . '.pdf';

            $qb->setOrderNumber($orderNumber);
            $qb->setVoucherCode($voucherCode);
            $qb->setGeneratedVoucher($generatedVoucher);
            $qb->setOrderStatus('paid');
        }

        $this->add($qb, true);

        return $qb;
    }

    public function savePayment($data, $orderUuid)
    {
        $qb = $this->findOneBy([
            'orderUuid' => $orderUuid,
        ]);

        if ($qb instanceof VoucherOrders) {
            $qb->setPaymentResponse($data['paymentResponse']);
            $qb->setPaymentStatus($data['paymentStatus']);
        }

        $this->add($qb, true);

        return $qb;
    }

    public function saveBeforePayment($data, $orderUuid = false)
    {
        if ($orderUuid) {
            $qb = $this->findOneBy([
                'orderUuid' => $orderUuid,
            ]);
        }

        if ($qb instanceof VoucherOrders) {
        } else {
            $qb = new VoucherOrders();
        }

        $qb->setLocale($data['locale']);
        $qb->setWebspaceKey($data['webspaceKey']);
        $qb->setIdCategories($this->getEntityManager()->getRepository(VoucherCategories::class)->findOneBy(['id' => $data['category']]));
        $qb->setOrderUuid($data['orderUuid']);
        $qb->setVoucherUuid($data['voucherUuid']);
        $qb->setVoucherAmount($data['voucherAmount']);
        $qb->setVoucherType($data['voucherType']);
        $qb->setVoucherHeadline($data['voucherHeadline']);
        $qb->setVoucherSubline($data['voucherSubline']);
        $qb->setVoucherHeader($data['voucherHeader']);
        $qb->setVoucherText($data['voucherText']);
        $qb->setVoucherMedia($this->getEntityManager()->getRepository(Media::class)->findOneBy(['id' => $data['voucherMedia']]));
        $qb->setFirstName($data['firstName']);
        $qb->setLastName($data['lastName']);
        $qb->setStreet($data['street']);
        $qb->setZip($data['zip']);
        $qb->setCity($data['city']);
        $qb->setCountry($data['country']);
        $qb->setEmail($data['email']);
        $qb->setPhone($data['phone']);
        $qb->setOrderStatus($data['orderStatus']);

        $this->add($qb, true);

        return $qb;
    }

    private function add(VoucherOrders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
}
