<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Api;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders as VoucherOrdersEntity;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\VirtualProperty;
use Sulu\Component\Rest\ApiWrapper;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

/**
 * The VoucherOrders class which will be exported to the API.
 */
#[ExclusionPolicy('all')]
class VoucherOrders extends ApiWrapper
{
    public function __construct(VoucherOrdersEntity $voucherOrders)
    {
        // @var VoucherOrdersEntity entity
        $this->entity = $voucherOrders;
    }

    #[VirtualProperty]
    #[SerializedName('id')]
    #[Groups(['fullVoucherOrders'])]
    public function getId(): ?int
    {
        return $this->entity->getId();
    }

    #[VirtualProperty]
    #[SerializedName('categoryName')]
    #[Groups(['fullVoucherOrders'])]
    public function getCategoryName()
    {
        return $this->entity->getIdCategories()->getName();
    }

    #[VirtualProperty]
    #[SerializedName('orderUuid')]
    #[Groups(['fullVoucherOrders'])]
    public function getOrderUuid()
    {
        return $this->entity->getOrderUuid();
    }

    #[VirtualProperty]
    #[SerializedName('orderNumber')]
    #[Groups(['fullVoucherOrders'])]
    public function getOrderNumber()
    {
        return $this->entity->getOrderNumber();
    }

    #[VirtualProperty]
    #[SerializedName('voucherCode')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherCode()
    {
        return $this->entity->getVoucherCode();
    }

    #[VirtualProperty]
    #[SerializedName('voucherAmount')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherAmount(): string
    {
        return 'EUR ' . \number_format($this->entity->getVoucherAmount(), 2, ',', '.');
    }

    #[VirtualProperty]
    #[SerializedName('voucherUuid')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherUuid()
    {
        return $this->entity->getVoucherUuid();
    }

    #[VirtualProperty]
    #[SerializedName('voucherMedia')]
    #[Groups(['fullVoucherOrders'])]
    public function getvoucherMedia()
    {
        return ($this->entity->getVoucherMedia()) ? ['id' => $this->entity->getVoucherMedia()->getId()] : [];
    }

    #[VirtualProperty]
    #[SerializedName('voucherType')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherType()
    {
        return $this->entity->getVoucherType();
    }

    #[VirtualProperty]
    #[SerializedName('voucherHeadline')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherHeadline()
    {
        return $this->entity->getVoucherHeadline();
    }

    #[VirtualProperty]
    #[SerializedName('voucherSubline')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherSubline()
    {
        return $this->entity->getVoucherSubline();
    }

    #[VirtualProperty]
    #[SerializedName('voucherHeader')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherHeader()
    {
        return $this->entity->getVoucherHeader();
    }

    #[VirtualProperty]
    #[SerializedName('voucherText')]
    #[Groups(['fullVoucherOrders'])]
    public function getVoucherText()
    {
        return $this->entity->getVoucherText();
    }

    #[VirtualProperty]
    #[SerializedName('firstName')]
    #[Groups(['fullVoucherOrders'])]
    public function getFirstName()
    {
        return $this->entity->getFirstName();
    }

    #[VirtualProperty]
    #[SerializedName('lastName')]
    #[Groups(['fullVoucherOrders'])]
    public function getLastName()
    {
        return $this->entity->getLastName();
    }

    #[VirtualProperty]
    #[SerializedName('street')]
    #[Groups(['fullVoucherOrders'])]
    public function getStreet()
    {
        return $this->entity->getStreet();
    }

    #[VirtualProperty]
    #[SerializedName('zip')]
    #[Groups(['fullVoucherOrders'])]
    public function getZip()
    {
        return $this->entity->getZip();
    }

    #[VirtualProperty]
    #[SerializedName('city')]
    #[Groups(['fullVoucherOrders'])]
    public function getCity()
    {
        return $this->entity->getCity();
    }

    #[VirtualProperty]
    #[SerializedName('country')]
    #[Groups(['fullVoucherOrders'])]
    public function getCountry()
    {
        return $this->entity->getCountry();
    }

    #[VirtualProperty]
    #[SerializedName('email')]
    #[Groups(['fullVoucherOrders'])]
    public function getEmail()
    {
        return $this->entity->getEmail();
    }

    #[VirtualProperty]
    #[SerializedName('phone')]
    #[Groups(['fullVoucherOrders'])]
    public function getPhone()
    {
        return $this->entity->getPhone();
    }

    #[VirtualProperty]
    #[SerializedName('orderStatus')]
    #[Groups(['fullVoucherOrders'])]
    public function getOrderStatus()
    {
        return $this->entity->getOrderStatus();
    }

    #[VirtualProperty]
    #[SerializedName('paymentResponse')]
    #[Groups(['fullVoucherOrders'])]
    public function getPaymentResponse()
    {
        return \is_array($this->entity->getPaymentResponse()) ? $this->getDataAsJsonElement($this->entity->getPaymentResponse()) : $this->entity->getPaymentResponse();
    }

    #[VirtualProperty]
    #[SerializedName('paymentStatus')]
    #[Groups(['fullVoucherOrders'])]
    public function getPaymentStatus()
    {
        return $this->entity->getPaymentStatus();
    }

    #[VirtualProperty]
    #[SerializedName('generatedVoucher')]
    #[Groups(['fullVoucherOrders'])]
    public function getGeneratedVoucher()
    {
        return $this->entity->getGeneratedVoucher();
    }

    #[VirtualProperty]
    #[SerializedName('generatedVoucherFileExists')]
    #[Groups(['fullVoucherOrders'])]
    public function generatedVoucherFileExists(): bool
    {
        $voucherStorageFolder = \dirname(__DIR__) . '/../../../var/voucher/pdf';

        return \file_exists($voucherStorageFolder . '/' . $this->entity->getGeneratedVoucher());
    }

    #[VirtualProperty]
    #[SerializedName('generatedVoucherFileExistsMessage')]
    #[Groups(['fullVoucherOrders'])]
    public function generatedVoucherFileExistsMessage(): string
    {
        if ($this->generatedVoucherFileExists()) {
            return $this->entity->getGeneratedVoucher() . '  ✅';
        }

        return $this->entity->getGeneratedVoucher() . '  ⚠️';
    }

    #[VirtualProperty]
    #[SerializedName('redeemed')]
    #[Groups(['fullVoucherOrders'])]
    public function getRedeemed()
    {
        return $this->entity->getRedeemed();
    }

    #[VirtualProperty]
    #[SerializedName('redeemedName')]
    #[Groups(['fullVoucherOrders'])]
    public function getRedeemedName()
    {
        return $this->entity->getRedeemedName();
    }

    #[VirtualProperty]
    #[SerializedName('voucherSent')]
    #[Groups(['fullVoucherOrders'])]
    public function getvoucherSent()
    {
        return $this->entity->getVoucherSent();
    }

    #[VirtualProperty]
    #[SerializedName('dateCreated')]
    #[Groups(['fullVoucherOrders'])]
    public function getDateCreated()
    {
        return $this->entity->getCreated();
    }

    private function getDataAsJsonElement(array $dataElement): string
    {
        return (new JsonEncoder())->encode($dataElement, 'json');
    }
}
