<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Twig;

use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherCategoriesRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VoucherExtension extends AbstractExtension
{
    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly VoucherCategoriesRepository $voucherCategoriesRepository,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('voucherCategories', $this->voucherCategories(...)),
        ];
    }

    public function voucherCategories($webspaceKey): array
    {
        $request = $this->requestStack->getCurrentRequest();

        return $this->voucherCategoriesRepository->showAllEnabled($webspaceKey, $request->getLocale());
    }
}
