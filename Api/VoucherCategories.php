<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Api;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategories as VoucherCategoriesEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Sulu\Component\Rest\ApiWrapper;

/**
 * The VoucherCategories class which will be exported to the API.
 *
 * @ExclusionPolicy("all")
 */
class VoucherCategories extends ApiWrapper
{
    public function __construct(VoucherCategoriesEntity $voucherCategories, $locale)
    {
        /* @var VoucherCategoriesEntity entity */
        $this->entity = $voucherCategories;
        $this->locale = $locale;
    }

    /**
     * Get id.
     *
     * @VirtualProperty
     *
     * @SerializedName("id")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getId(): ?int
    {
        return $this->entity->getId();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("webspaceSettings")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getWebspaceSettings()
    {
        return $this->entity->getWebspaceSettings();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("webspaceKey")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getWebspaceKey()
    {
        return $this->entity->getWebspaceKey();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("name")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getName()
    {
        return $this->entity->getName();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("amount")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getAmount()
    {
        return $this->entity->getAmount();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("enabled")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function isEnabled()
    {
        return $this->entity->isEnabled();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("position")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getPosition()
    {
        return $this->entity->getPosition();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("translation")
     *
     * @Groups({"fullVoucherCategories"})
     */
    public function getTranslation(): array
    {
        $translations = $this->entity->getVoucherCategoryTranslations();

        $result = [
            'data' => '',
            'name' => '',
            'description' => '',
            'preview_image' => null,
        ];

        if (null !== $this->locale) {
            foreach ($translations as $translation) {
                if ($this->locale === $translation->getLocale()) {
                    $result['data'] = $translation->getData();
                    $result['name'] = $translation->getName();
                    $result['description'] = $translation->getDescription();
                    $result['preview_image'] = ($translation->getPreviewImage()) ? ['id' => $translation->getPreviewImage()->getId()] : [];
                }
            }
        }

        return $result;
    }
}
