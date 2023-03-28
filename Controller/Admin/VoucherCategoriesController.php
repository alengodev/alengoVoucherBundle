<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Controller\Admin;

use Alengo\Bundle\AlengoVoucherBundle\Api\VoucherCategories as VoucherCategoriesApi;
use Alengo\Bundle\AlengoVoucherBundle\Common\DoctrineListRepresentationFactory;
use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategories;
use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherCategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\View\ViewHandlerInterface;
use HandcraftedInTheAlps\RestRoutingBundle\Controller\Annotations\RouteResource;
use HandcraftedInTheAlps\RestRoutingBundle\Routing\ClassResourceInterface;
use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;
use Sulu\Component\Rest\AbstractRestController;
use Sulu\Component\Security\SecuredControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @RouteResource("voucher-categories")
 */
class VoucherCategoriesController extends AbstractRestController implements ClassResourceInterface, SecuredControllerInterface
{
    // serialization groups for voucher categories
    protected static $oneVoucherCategoriesSerializationGroups = [
        'fullVoucherCategories',
    ];

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly VoucherCategoriesRepository $voucherCategoriesRepository,
        private readonly MediaRepositoryInterface $mediaRepository,
        private readonly DoctrineListRepresentationFactory $doctrineListRepresentationFactory,
        ViewHandlerInterface $viewHandler,
        ?TokenStorageInterface $tokenStorage = null,
    ) {
        parent::__construct($viewHandler, $tokenStorage);
    }

    public function cgetAction(Request $request): Response
    {
        $locale = $request->query->get('locale');
        $listRepresentation = $this->doctrineListRepresentationFactory->createDoctrineListRepresentation(
            VoucherCategories::RESOURCE_KEY,
            [],
            ['locale' => $locale],
        );

        return $this->handleView($this->view($listRepresentation));
    }

    public function getAction(int $id, Request $request): Response
    {
        $voucherCategories = $this->entityManager->getRepository(VoucherCategories::class)->find($id);
        if (!$voucherCategories instanceof VoucherCategories) {
            throw new NotFoundHttpException();
        }

        $apiEntity = $this->generateApiVoucherCategoriesEntity($voucherCategories, $request->query->get('locale'));
        $view = $this->generateViewContent($apiEntity);

        return $this->handleView($view);
    }

    public function postAction(Request $request): Response
    {
        $data = $this->mapDataToEntity($request);
        $voucherCategories = $this->voucherCategoriesRepository->create($data);

        return $this->handleView($this->view($voucherCategories, 201));
    }

    /**
     * @Rest\Post("/voucher-categories/{id}")
     */
    public function postTriggerAction(int $id, Request $request): Response
    {
        $voucherCategories = $this->entityManager->getRepository(VoucherCategories::class)->find($id);
        if (!$voucherCategories instanceof VoucherCategories) {
            throw new NotFoundHttpException();
        }

        switch ($request->query->get('action')) {
            case 'enable':
                $voucherCategories->setEnabled(true);
                break;
            case 'disable':
                $voucherCategories->setEnabled(false);
                break;
        }

        $this->entityManager->persist($voucherCategories);
        $this->entityManager->flush();

        return $this->handleView($this->view($voucherCategories));
    }

    public function putAction(Request $request, int $id): Response
    {
        $data = $this->mapDataToEntity($request);
        $voucherCategories = $this->voucherCategoriesRepository->save($data, $id);

        return $this->handleView($this->view($voucherCategories, 201));
    }

    public function deleteAction(int $id): Response
    {
        $voucherCategories = $this->voucherCategoriesRepository->remove($id);

        return $this->handleView($this->view(null, 204));
    }

    protected function mapDataToEntity($request)
    {
        $data = $request->request->all();

        $data['translation']['locale'] = $request->query->get('locale');

        $previewImage = null;
        if ($previewImageId = ($data['translation']['preview_image']['id'] ?? null)) {
            $previewImage = $this->mediaRepository->findMediaById($previewImageId);
        }
        $data['translation']['preview_image'] = $previewImage;

        $voucherImage = null;
        if ($voucherImageId = ($data['translation']['voucher_image']['id'] ?? null)) {
            $voucherImage = $this->mediaRepository->findMediaById($voucherImageId);
        }
        $data['translation']['voucher_image'] = $voucherImage;

        $voucherBackgroundImage = null;
        if ($voucherBackgroundImageId = ($data['translation']['voucher_background_image']['id'] ?? null)) {
            $voucherBackgroundImage = $this->mediaRepository->findMediaById($voucherBackgroundImageId);
        }
        $data['translation']['voucher_background_image'] = $voucherBackgroundImage;

        $data['enabled'] = false;

        return $data;
    }

    protected function generateApiVoucherCategoriesEntity(VoucherCategories $entity, $locale): VoucherCategoriesApi
    {
        return new VoucherCategoriesApi($entity, $locale);
    }

    protected function generateViewContent(VoucherCategoriesApi $entity): View
    {
        $view = $this->view($entity);
        $context = new Context();
        $context->setGroups(static::$oneVoucherCategoriesSerializationGroups);

        return $view->setContext($context);
    }

    public function getSecurityContext(): string
    {
        return VoucherCategories::SECURITY_CONTEXT;
    }
}
