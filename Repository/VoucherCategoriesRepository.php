<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Repository;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method VoucherCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method VoucherCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method VoucherCategories[] findAll()
 * @method VoucherCategories[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly ManagerRegistry $registry,
        private readonly VoucherCategoryTranslationsRepository $voucherCategoryTranslationsRepository,
    ) {
        parent::__construct($registry, VoucherCategories::class);
    }

    public function create($data)
    {
        $qb = new VoucherCategories();
        $qb->setWebspaceSettings($data['webspaceSettings'] ?? false);
        $qb->setWebspaceKey($data['webspaceKey'] ?? '');
        $qb->setName($data['name']);
        $qb->setPosition($data['position'] ?? 0);
        $qb->setEnabled((bool) $data['enabled']);
        $qb->addVoucherCategoryTranslation($this->voucherCategoryTranslationsRepository->create($data['translation']));
        $qb->setCreated(new \DateTime());

        $this->getEntityManager()->persist($qb);
        $this->getEntityManager()->flush();

        return $qb;
    }

    public function save($data, int $id)
    {
        $qb = $this->find($id);

        if (!$qb instanceof VoucherCategories) {
            throw new NotFoundHttpException(
                'No voucher data found for id ' . $id,
            );
        }

        $qb->setWebspaceSettings((bool) $data['webspaceSettings']);
        $qb->setWebspaceKey($data['webspaceKey'] && $data['webspaceSettings'] ? $data['webspaceKey'] : '');
        $qb->setName($data['name']);
        $qb->setPosition($data['position'] ?? 0);

        $translationId = false;
        foreach ($qb->getVoucherCategoryTranslations() as $translation) {
            if ($translation->getLocale() === $data['translation']['locale']) {
                $translationId = $translation->getId();
            }
        }

        if ($translationId) {
            $qb->addVoucherCategoryTranslation($this->voucherCategoryTranslationsRepository->save($data['translation'], $translationId));
        } else {
            $qb->addVoucherCategoryTranslation($this->voucherCategoryTranslationsRepository->create($data['translation']));
        }

        $qb->setChanged(new \DateTime());

        $this->getEntityManager()->persist($qb);
        $this->getEntityManager()->flush();

        return $qb;
    }

    public function get(int $id)
    {
        $qb = $this->find($id);

        if (!$qb instanceof VoucherCategories) {
            throw new NotFoundHttpException(
                'No voucher data found for id ' . $id,
            );
        }

        return $qb;
    }

    public function remove(int $id): string
    {
        $qb = $this->find($id);

        if (!$qb instanceof VoucherCategories) {
            throw new NotFoundHttpException(
                'No weather data found for id ' . $id,
            );
        }

        foreach ($qb->getVoucherCategoryTranslations() as $translation) {
            $this->voucherCategoryTranslationsRepository->remove($translation->getId());
        }

        $this->getEntityManager()->remove($qb);
        $this->getEntityManager()->flush();

        return 'Deleted voucher data with id ' . $id;
    }

    // /**
    //  * @return VoucherCategories[] Returns an array of VoucherCategories objects
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
    public function findOneBySomeField($value): ?VoucherCategories
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
