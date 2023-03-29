<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Service;

use Alengo\Bundle\AlengoVoucherBundle\Entity\Factory\MediaFactoryInterface;
use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherCategoriesRepository;

class VoucherCategoriesService
{
    public function __construct(
        private readonly VoucherCategoriesRepository $voucherCategoriesRepository,
        private readonly bool $voucherPerWebspace,
        private readonly MediaFactoryInterface $mediaFactory,
    ) {
    }

    public function getAllEnabled($webspaceKey, $category = false, $locale = false): array
    {
        $result = null;

        $filter = [
            'enabled' => 1,
        ];

        if ($this->voucherPerWebspace) {
            $filter['webspaceKey'] = $webspaceKey;
        }

        if ($category) {
            $filter['id'] = $category;
        }

        $qb = $this->voucherCategoriesRepository->findBy(
            $filter,
            ['position' => 'ASC'],
        );

        if ([] === $qb) {
            return $qb;
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

    public function getVoucherByUuid($uuid, $webspaceKey, $locale = false): array
    {
        $data = $this->getAllEnabled($webspaceKey, false, $locale);
        $values = [];

        foreach ($data as $key_category => $value_category) {
            foreach ($value_category['data'] as $key => $value) {
                $values[$value['uuid']] = $value;
            }
        }

        return $values[$uuid];
    }
}
