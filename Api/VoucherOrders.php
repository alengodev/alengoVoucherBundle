<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Api;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders as VoucherOrdersEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Sulu\Component\Rest\ApiWrapper;

/**
 * The VoucherOrders class which will be exported to the API.
 *
 * @ExclusionPolicy("all")
 */
class VoucherOrders extends ApiWrapper
{
    public function __construct(VoucherOrdersEntity $voucherOrders)
    {
        /* @var VoucherOrdersEntity entity */
        $this->entity = $voucherOrders;
    }

    /**
     * Get id.
     *
     * @VirtualProperty
     *
     * @SerializedName("id")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getId(): ?int
    {
        return $this->entity->getId();
    }

    /**
     * Get id.
     *
     * @VirtualProperty
     *
     * @SerializedName("categoryName")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getCategoryName()
    {
        return $this->entity->getIdCategories()->getName();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("orderNumber")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getOrderNumber()
    {
        return $this->entity->getOrderNumber();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("voucherCode")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getVoucherCode()
    {
        return \str_replace('.pdf', '', (string) $this->entity->getGeneratedVoucher());
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("voucherAmount")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getVoucherAmount(): string
    {
        return 'EUR ' . \number_format($this->entity->getVoucherAmount(), 2, ',', '.');
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("voucherHeader")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getVoucherHeader()
    {
        return $this->entity->getVoucherHeader();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("voucherText")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getVoucherText()
    {
        return $this->entity->getVoucherText();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("firstName")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getFirstName()
    {
        return $this->entity->getFirstName();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("lastName")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getLastName()
    {
        return $this->entity->getLastName();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("street")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getStreet()
    {
        return $this->entity->getStreet();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("zip")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getZip()
    {
        return $this->entity->getZip();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("city")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getCity()
    {
        return $this->entity->getCity();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("email")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getEmail()
    {
        return $this->entity->getEmail();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("phone")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getPhone()
    {
        return $this->entity->getPhone();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("generatedVoucher")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getGeneratedVoucher()
    {
        return $this->entity->getGeneratedVoucher();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("generatedVoucherFileExists")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function generatedVoucherFileExists(): bool
    {
        $voucherStorageFolder = \dirname(__DIR__) . '/../var/voucher';

        return \file_exists($voucherStorageFolder . '/' . $this->entity->getGeneratedVoucher());
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("generatedVoucherFileExistsMessage")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function generatedVoucherFileExistsMessage(): string
    {
        if ($this->generatedVoucherFileExists()) {
            return $this->entity->getGeneratedVoucher() . '  ✅';
        }

        return $this->entity->getGeneratedVoucher() . '  ⚠️';
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("redeemed")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getRedeemed()
    {
        return $this->entity->getRedeemed();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("redeemedName")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getRedeemedName()
    {
        return $this->entity->getRedeemedName();
    }

    /**
     * @VirtualProperty
     *
     * @SerializedName("dateCreated")
     *
     * @Groups({"fullVoucherOrders"})
     */
    public function getDateCreated()
    {
        return $this->entity->getCreated();
    }
}
