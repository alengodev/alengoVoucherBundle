<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Service;

use Alengo\Bundle\AlengoVoucherBundle\Entity\Factory\MediaFactoryInterface;
use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherCategoriesRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoucherCategoriesService
{
    public function __construct(
        private readonly VoucherCategoriesRepository $voucherCategoriesRepository,
        private readonly bool $voucherPerWebspace,
        private readonly MediaFactoryInterface $mediaFactory,
    ) {
    }

    public function getAllEnabled($webspaceKey, $locale = false): array
    {
        $result = null;

        if ($this->voucherPerWebspace) {
            $qb = $this->voucherCategoriesRepository->findBy(
                [
                    'enabled' => 1,
                    'webspaceKey' => $webspaceKey,
                ],
                ['position' => 'ASC'],
            );
        } else {
            $qb = $this->voucherCategoriesRepository->findBy(
                ['enabled' => 1],
                ['position' => 'ASC'],
            );
        }

        if ([] === $qb) {
            throw new NotFoundHttpException(
                'No data found',
            );
        }

        foreach ($qb as $key => $value) {
            $result[$key]['id'] = $value->getId();
            $result[$key]['name'] = $value->getName();

            foreach ($value->getVoucherCategoryTranslations() as $translation) {
                if (isset($locale) && $locale === $translation->getLocale()) {
                    $result[$key]['excerptTitle'] = $translation->getName();
                    $result[$key]['excerptDescription'] = $translation->getDescription();
                    $result[$key]['excerptImage'] = $this->mediaFactory->getMedia($translation->getPreviewImage());
                    $result[$key]['data'] = $translation->getData();
                } else {
                    $result[$key][$translation->getLocale()]['excerptTitle'] = $translation->getName();
                    $result[$key][$translation->getLocale()]['excerptDescription'] = $translation->getDescription();
                    $result[$key][$translation->getLocale()]['excerptImage'] = $this->mediaFactory->getMedia($translation->getPreviewImage());
                    $result[$key][$translation->getLocale()]['data'] = $translation->getData();
                }
            }
        }

        return $result;
    }
}
