<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Preview;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherOrdersRepository;
use Sulu\Bundle\PreviewBundle\Preview\Object\PreviewObjectProviderInterface;

class VoucherObjectProvider implements PreviewObjectProviderInterface
{
    public function __construct(
        private readonly VoucherOrdersRepository $voucherOrdersRepository,
    ) {
    }

    public function getObject($id, $locale): ?VoucherOrders
    {
        return $this->voucherOrdersRepository->findOneBy(['id' => $id]);
    }

    public function getId($object)
    {
        return $object->getId();
    }

    public function setValues($object, $locale, array $data): void
    {
    }

    public function setContext($object, $locale, array $context): VoucherOrders
    {
    }

    public function serialize($object): string
    {
        return \serialize($object);
    }

    public function deserialize($serializedObject, $objectClass): VoucherOrders
    {
        return \unserialize($serializedObject);
    }

    public function getSecurityContext($id, $locale): ?string
    {
        return VoucherOrders::SECURITY_CONTEXT;
    }
}
