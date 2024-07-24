<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Controller;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Sulu\Bundle\PreviewBundle\Preview\Preview;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VoucherController extends AbstractController
{
    public function indexAction(VoucherOrders $voucherOrders, $attributes = [], $preview = false, $partial = false): Response
    {
        if (!$voucherOrders) {
            throw new NotFoundHttpException();
        }

        if ($partial) {
            $content = $this->renderBlockView(
                'voucher/orders.html.twig',
                'content',
                ['voucher' => $voucherOrders],
            );
        } elseif ($preview) {
            $content = $this->renderPreview(
                'voucher/orders.html.twig',
                ['voucher' => $voucherOrders],
            );
        } else {
            $content = $this->renderView(
                'voucher/orders.html.twig',
                ['voucher' => $voucherOrders],
            );
        }

        return new Response($content);
    }

    protected function renderPreview(string $view, array $parameters = []): string
    {
        $parameters['previewParentTemplate'] = $view;
        $parameters['previewContentReplacer'] = Preview::CONTENT_REPLACER;

        return $this->renderView('@SuluWebsite/Preview/preview.html.twig', $parameters);
    }

    protected function renderBlockView($template, $block, $attributes = [], ?Response $response = null): string
    {
        $twig = $this->container->get('twig');
        $attributes = $twig->mergeGlobals($attributes);

        $template = $twig->load($template);

        $level = \ob_get_level();
        \ob_start();

        try {
            $rendered = $template->renderBlock($block, $attributes);
            \ob_end_clean();

            return $rendered;
        } catch (\Exception $e) {
            while (\ob_get_level() > $level) {
                \ob_end_clean();
            }

            throw $e;
        }
    }
}
