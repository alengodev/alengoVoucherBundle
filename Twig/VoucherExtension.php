<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Twig;

use Alengo\Bundle\AlengoVoucherBundle\Service\VoucherCategoriesService;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VoucherExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly VoucherCategoriesService $voucherCategoriesService,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('voucherCategories', $this->voucherCategories(...)),
            new TwigFunction('voucherUuid', $this->voucherUuid(...)),
        ];
    }

    public function voucherCategories($webspaceKey, $category = false): array
    {
        $request = $this->requestStack->getCurrentRequest();

        return $this->voucherCategoriesService->getAllEnabled($webspaceKey, $category, $request->getLocale());
    }

    public function voucherUuid($uuid, $webspaceKey): array
    {
        $request = $this->requestStack->getCurrentRequest();

        return $this->voucherCategoriesService->getVoucherByUuid($uuid, $webspaceKey, $request->getLocale());
    }
}
