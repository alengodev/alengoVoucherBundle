<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Repository;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategoryTranslations;
use Alengo\Bundle\AlengoVoucherBundle\Helper\DataModifier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method VoucherCategoryTranslations|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherCategoryTranslations|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherCategoryTranslations[] findAll()
 * @method VoucherCategoryTranslations[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherCategoryTranslationsRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly DataModifier $dataModifier,
    ) {
        parent::__construct($registry, VoucherCategoryTranslations::class);
    }

    public function create($data)
    {
        $qb = new VoucherCategoryTranslations();
        $qb->setLocale($data['locale']);
        $qb->setData($this->dataModifier->modifyData($data['data']) ?? []);
        $qb->setName($data['name'] ?? '');
        $qb->setDescription($data['description'] ?? '');
        $qb->setPreviewImage($data['preview_image'] ?? null);
        $qb->setCountedVouchers(\is_countable($data['data']) ? \count($data['data']) : 0);
        $qb->setCreated(new \DateTime());

        $this->getEntityManager()->persist($qb);
        $this->getEntityManager()->flush();

        return $qb;
    }

    public function save($data, int $id)
    {
        $qb = $this->find($id);

        if (!$qb instanceof VoucherCategoryTranslations) {
            throw new NotFoundHttpException(
                'No voucher translation data found for id ' . $id,
            );
        }

        $qb->setLocale($data['locale']);
        $qb->setData($this->dataModifier->modifyData($data['data']) ?? []);
        $qb->setName($data['name'] ?? '');
        $qb->setDescription($data['description'] ?? '');
        $qb->setPreviewImage($data['preview_image'] ?? null);
        $qb->setCountedVouchers(\is_countable($data['data']) ? \count($data['data']) : 0);
        $qb->setChanged(new \DateTime());

        $this->getEntityManager()->persist($qb);
        $this->getEntityManager()->flush();

        return $qb;
    }

    public function get(int $id)
    {
        $qb = $this->find($id);

        if (!$qb instanceof VoucherCategoryTranslations) {
            throw new NotFoundHttpException(
                'No voucher data found for id ' . $id,
            );
        }

        return $qb;
    }

    public function remove(int $id)
    {
        $qb = $this->find($id);

        if (!$qb instanceof VoucherCategoryTranslations) {
            throw new NotFoundHttpException(
                'No weather data found for id ' . $id,
            );
        }

        $this->getEntityManager()->remove($qb);
        $this->getEntityManager()->flush();

        return $qb;
    }

    // /**
    //  * @return VoucherCategoryTranslations[] Returns an array of VoucherCategoryTranslations objects
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
    public function findOneBySomeField($value): ?VoucherCategoryTranslations
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
