<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Routing;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherOrdersRepository;
use Sulu\Bundle\RouteBundle\Routing\Defaults\RouteDefaultsProviderInterface;

class VoucherRouteDefaultProvider implements RouteDefaultsProviderInterface
{
    public function __construct(
        private readonly VoucherOrdersRepository $voucherOrdersRepository,
    ) {
    }

    public function getByEntity($entityClass, $id, $locale = '', $object = null)
    {
        return [
            '_controller' => 'alengo_voucher.controller::indexAction',
            'voucherOrders' => $object ?: $this->voucherOrdersRepository->findOneBy(['id' => $id, 'locale' => $locale]),
        ];
    }

    public function isPublished($entityClass, $id, $locale)
    {
        return true;
    }

    public function supports($entityClass)
    {
        return VoucherOrders::class === $entityClass;
    }
}
