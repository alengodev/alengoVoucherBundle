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
    /**
     * @var ManagerRegistry|mixed
     */
    public $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;

        parent::__construct($registry, VoucherCategories::class);
    }

    public function create($data)
    {
        $qb = new VoucherCategories();
        $qb->setName($data['name']);
        $qb->setAmount($data['amount'] ?? 0);
        $qb->setPosition($data['position'] ?? 0);
        $qb->setEnabled($data['enabled'] ?? 0);
        $qb->addVoucherCategoryTranslation((new VoucherCategoryTranslationsRepository($this->registry))->create($data['translation']));

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

        $qb->setName($data['name']);
        $qb->setAmount($data['amount'] ?? 0);
        $qb->setPosition($data['position'] ?? 0);

        $translationId = false;
        foreach ($qb->getVoucherCategoryTranslations() as $translation) {
            if ($translation->getLocale() === $data['translation']['locale']) {
                $translationId = $translation->getId();
            }
        }

        if ($translationId) {
            $qb->addVoucherCategoryTranslation((new VoucherCategoryTranslationsRepository($this->registry))->save($data['translation'], $translationId));
        } else {
            $qb->addVoucherCategoryTranslation((new VoucherCategoryTranslationsRepository($this->registry))->create($data['translation']));
        }

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
            (new VoucherCategoryTranslationsRepository($this->registry))->remove($translation->getId());
        }

        $this->getEntityManager()->remove($qb);
        $this->getEntityManager()->flush();

        return 'Deleted voucher data with id ' . $id;
    }

    public function showAllEnabled($locale = false)
    {
        $qb = $this->findBy(
            ['enabled' => 1],
            ['position' => 'ASC'],
        );

        if ([] === $qb) {
            throw new NotFoundHttpException(
                'No data found',
            );
        }

        foreach ($qb as $key => $value) {
            $result[$key]['id'] = $value->getId();
            $result[$key]['name'] = $value->getName();
            $result[$key]['amount'] = $value->getAmount();

            foreach ($value->getVoucherCategoryTranslations() as $translation) {
                if (isset($locale) && $locale === $translation->getLocale()) {
                    $result[$key]['translation']['name'] = $translation->getName();
                    $result[$key]['translation']['description'] = $translation->getDescription();
                    $result[$key]['translation']['preview_image'] = $translation->getPreviewImage();
                    $result[$key]['translation']['pdf_image'] = $translation->getPdfImage();
                } else {
                    $result[$key][$translation->getLocale()]['name'] = $translation->getName();
                    $result[$key][$translation->getLocale()]['description'] = $translation->getDescription();
                    $result[$key][$translation->getLocale()]['preview_image'] = $translation->getPreviewImage();
                    $result[$key][$translation->getLocale()]['pdf_image'] = $translation->getPdfImage();
                }
            }
        }

        return $result;
    }

    public function showVoucherImages($categoryId, $locale = false)
    {
        $qb = $this->findOneBy(
            ['id' => $categoryId],
        );

        $qb = [$qb];

        foreach ($qb as $key => $value) {
            $result[$key]['id'] = $value->getId();
            $result[$key]['name'] = $value->getName();
            $result[$key]['amount'] = $value->getAmount();

            foreach ($value->getVoucherCategoryTranslations() as $translation) {
                if (isset($locale) && $locale === $translation->getLocale()) {
                    $result[$key]['preview_image'] = $translation->getPreviewImage();
                    $result[$key]['pdf_image'] = $translation->getPdfImage();
                } else {
                    $result[$key][$translation->getLocale()]['preview_image'] = $translation->getPreviewImage();
                    $result[$key][$translation->getLocale()]['pdf_image'] = $translation->getPdfImage();
                }
            }
        }

        return $result;
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
