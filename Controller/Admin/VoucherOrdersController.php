<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Controller\Admin;

use Alengo\Bundle\AlengoVoucherBundle\Api\VoucherOrders as VoucherOrdersApi;
use Alengo\Bundle\AlengoVoucherBundle\Common\DoctrineListRepresentationFactory;
use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use HandcraftedInTheAlps\RestRoutingBundle\Controller\Annotations\RouteResource;
use HandcraftedInTheAlps\RestRoutingBundle\Routing\ClassResourceInterface;
use Sulu\Component\Rest\AbstractRestController;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @RouteResource("voucher-orders")
 */
class VoucherOrdersController extends AbstractRestController implements ClassResourceInterface, SecuredControllerInterface
{
    // serialization groups for voucher orders
    protected static $oneVoucherOrdersSerializationGroups = [
        'fullVoucherOrders',
    ];

    public function __construct(
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        private readonly EntityManagerInterface $entityManager,
        ViewHandlerInterface $viewHandler,
        TokenStorageInterface $tokenStorage = null,
    ) {
        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(): Response
    {
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            VoucherOrders::RESOURCE_KEY,
        );

        return $this->handleView($this->view($listRepresentation));
    }

    public function getAction(int $id): Response
    {
        $voucherOrders = $this->entityManager->getRepository(VoucherOrders::class)->find($id);
        if (!$voucherOrders instanceof VoucherOrders) {
            throw new NotFoundHttpException();
        }

        $apiEntity = $this->generateApiVoucherOrdersEntity($voucherOrders);
        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);

        // return $this->handleView($this->view($voucherOrders));
    }

    public function putAction(Request $request, int $id): Response
    {
        $voucherOrders = $this->entityManager->getRepository(VoucherOrders::class)->find($id);
        if (!$voucherOrders instanceof VoucherOrders) {
            throw new NotFoundHttpException();
        }

        $this->mapDataToEntity($request->request->all(), $voucherOrders);
        $this->entityManager->flush();

        return $this->handleView($this->view($voucherOrders));
    }

    public function postAction(Request $request): Response
    {
        $voucherOrders = new VoucherOrders();

        $this->mapDataToEntity($request->request->all(), $voucherOrders);
        $this->entityManager->persist($voucherOrders);
        $this->entityManager->flush();

        return $this->handleView($this->view($voucherOrders, 201));
    }

    public function deleteAction(int $id): Response
    {
        /** @var VoucherOrders $voucherOrders */
        $voucherOrders = $this->entityManager->getReference(VoucherOrders::class, $id);
        $this->entityManager->remove($voucherOrders);
        $this->entityManager->flush();

        return $this->handleView($this->view(null, 204));
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function mapDataToEntity(array $data, VoucherOrders $entity): void
    {
        $entity->setRedeemed($data['redeemed'] ? new \DateTime($data['redeemed']) : null);
        $entity->setRedeemedName($data['redeemedName'] ?? '');
        $entity->setVoucherSent($data['voucherSent']);
    }

    protected function generateApiVoucherOrdersEntity(VoucherOrders $entity): VoucherOrdersApi
    {
        return new VoucherOrdersApi($entity);
    }

    protected function generateViewContent(VoucherOrdersApi $entity): View
    {
        $view = $this->view($entity);
        $context = new Context();
        $context->setGroups(static::$oneVoucherOrdersSerializationGroups);

        return $view->setContext($context);
    }

    public function getSecurityContext(): string
    {
        return VoucherOrders::SECURITY_CONTEXT;
    }
}
