<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Admin;

use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherCategories;
use Alengo\Bundle\AlengoVoucherBundle\Entity\VoucherOrders;
use Sulu\Bundle\AdminBundle\Admin\Admin;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItem;
use Sulu\Bundle\AdminBundle\Admin\Navigation\NavigationItemCollection;
use Sulu\Bundle\AdminBundle\Admin\View\TogglerToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ToolbarAction;
use Sulu\Bundle\AdminBundle\Admin\View\ViewBuilderFactoryInterface;
use Sulu\Bundle\AdminBundle\Admin\View\ViewCollection;
use Sulu\Component\Security\Authorization\PermissionTypes;
use Sulu\Component\Security\Authorization\SecurityCheckerInterface;
use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;

class VoucherAdmin extends Admin
{
    final public const CATEGORIES_LIST_VIEW = 'app.voucher_categories.list';
    final public const CATEGORIES_ADD_FORM_VIEW = 'app.voucher_categories.add_form';
    final public const CATEGORIES_ADD_FORM_DETAILS_VIEW = 'app.voucher_categories.add_form.details';
    final public const CATEGORIES_EDIT_FORM_VIEW = 'app.voucher_categories.edit_form';
    final public const CATEGORIES_EDIT_FORM_DETAILS_VIEW = 'app.voucher_categories.edit_form.details';

    final public const ORDERS_LIST_VIEW = 'app.voucher_orders.list';
    final public const ORDERS_ADD_FORM_VIEW = 'app.voucher_orders.add_form';
    final public const ORDERS_ADD_FORM_DETAILS_VIEW = 'app.voucher_orders.add_form.details';
    final public const ORDERS_EDIT_FORM_VIEW = 'app.voucher_orders.edit_form';
    final public const ORDERS_EDIT_FORM_DETAILS_VIEW = 'app.voucher_orders.edit_form.details';

    public function __construct(
        private readonly ViewBuilderFactoryInterface $viewBuilderFactory,
        private readonly WebspaceManagerInterface $webspaceManager,
        private readonly SecurityCheckerInterface $securityChecker
    ) {
    }

    public function configureNavigationItems(NavigationItemCollection $navigationItemCollection): void
    {
        if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::EDIT) || $this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
            $rootNavigationItem = new NavigationItem('app.voucher');
            $rootNavigationItem->setIcon('fa-gift');
            $rootNavigationItem->setPosition(32);

            if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                $navigationItem = new NavigationItem('app.voucher.categories');
                $navigationItem->setPosition(10);
                $navigationItem->setView(static::CATEGORIES_LIST_VIEW);

                $rootNavigationItem->addChild($navigationItem);
            }

            if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                $navigationItem = new NavigationItem('app.orders');
                $navigationItem->setPosition(20);
                $navigationItem->setView(static::ORDERS_LIST_VIEW);

                $rootNavigationItem->addChild($navigationItem);
            }

            $navigationItemCollection->add($rootNavigationItem);
        }
    }

    public function configureViews(ViewCollection $viewCollection): void
    {
        if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
            $locales = $this->webspaceManager->getAllLocales();

            $formToolbarActions = [];
            $listToolbarActions = [];

            if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::ADD)) {
                $listToolbarActions[] = new ToolbarAction('sulu_admin.add');
            }

            if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                $formToolbarActions[] = new ToolbarAction('sulu_admin.save');
            }

            if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::DELETE)) {
                $formToolbarActions[] = new ToolbarAction('sulu_admin.delete');
                $listToolbarActions[] = new ToolbarAction('sulu_admin.delete');
            }

            if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::VIEW)) {
                $listToolbarActions[] = new ToolbarAction('sulu_admin.export');
            }

            if ($this->securityChecker->hasPermission(VoucherCategories::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                $formToolbarActions[] = new TogglerToolbarAction(
                    'app.enable',
                    'enabled',
                    'enable',
                    'disable',
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createListViewBuilder(static::CATEGORIES_LIST_VIEW, '/voucher-categories/:locale')
                        ->setResourceKey(VoucherCategories::RESOURCE_KEY)
                        ->setListKey(VoucherCategories::LIST_KEY)
                        ->setTitle('app.voucher.categories')
                        ->addListAdapters(['table'])
                        ->addLocales($locales)
                        ->setDefaultLocale($locales[0])
                        ->setAddView(static::CATEGORIES_ADD_FORM_VIEW)
                        ->setEditView(static::CATEGORIES_EDIT_FORM_VIEW)
                        ->addToolbarActions($listToolbarActions),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createResourceTabViewBuilder(static::CATEGORIES_ADD_FORM_VIEW, '/voucher-categories/:locale/add')
                        ->setResourceKey(VoucherCategories::RESOURCE_KEY)
                        ->setBackView(static::CATEGORIES_LIST_VIEW)
                        ->addLocales($locales),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createFormViewBuilder(static::CATEGORIES_ADD_FORM_DETAILS_VIEW, '/details')
                        ->setResourceKey(VoucherCategories::RESOURCE_KEY)
                        ->setFormKey(VoucherCategories::FORM_KEY)
                        ->setTabTitle('sulu_admin.details')
                        ->setEditView(static::CATEGORIES_EDIT_FORM_VIEW)
                        ->addToolbarActions($formToolbarActions)
                        ->setParent(static::CATEGORIES_ADD_FORM_VIEW),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createResourceTabViewBuilder(static::CATEGORIES_EDIT_FORM_VIEW, '/voucher-categories/:locale/:id')
                        ->setResourceKey(VoucherCategories::RESOURCE_KEY)
                        ->setBackView(static::CATEGORIES_LIST_VIEW)
                        ->addLocales($locales),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createFormViewBuilder(static::CATEGORIES_EDIT_FORM_DETAILS_VIEW, '/details')
                        ->setResourceKey(VoucherCategories::RESOURCE_KEY)
                        ->setFormKey(VoucherCategories::FORM_KEY)
                        ->setTabTitle('sulu_admin.details')
                        ->addToolbarActions($formToolbarActions)
                        ->setParent(static::CATEGORIES_EDIT_FORM_VIEW),
                );
            }
        }

        if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
            $formToolbarActions = [];
            $listToolbarActions = [];

            if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::ADD)) {
                $listToolbarActions[] = new ToolbarAction('sulu_admin.add');
            }

            if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                $formToolbarActions[] = new ToolbarAction('sulu_admin.save');
            }

            if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::DELETE)) {
                $formToolbarActions[] = new ToolbarAction('sulu_admin.delete');
                $listToolbarActions[] = new ToolbarAction('sulu_admin.delete');
            }

            if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::VIEW)) {
                $listToolbarActions[] = new ToolbarAction('sulu_admin.export');
            }

            if ($this->securityChecker->hasPermission(VoucherOrders::SECURITY_CONTEXT, PermissionTypes::EDIT)) {
                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createListViewBuilder(static::ORDERS_LIST_VIEW, '/voucher-orders')
                        ->setResourceKey(VoucherOrders::RESOURCE_KEY)
                        ->setListKey(VoucherOrders::LIST_KEY)
                        ->setTitle('app.orders')
                        ->addListAdapters(['table'])
                        ->setAddView(static::ORDERS_ADD_FORM_VIEW)
                        ->setEditView(static::ORDERS_EDIT_FORM_VIEW)
                        ->addToolbarActions($listToolbarActions),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createResourceTabViewBuilder(static::ORDERS_ADD_FORM_VIEW, '/voucher-orders/add')
                        ->setResourceKey(VoucherOrders::RESOURCE_KEY)
                        ->setBackView(static::ORDERS_LIST_VIEW),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createFormViewBuilder(static::ORDERS_ADD_FORM_DETAILS_VIEW, '/details')
                        ->setResourceKey(VoucherOrders::RESOURCE_KEY)
                        ->setFormKey(VoucherOrders::FORM_KEY)
                        ->setTabTitle('sulu_admin.details')
                        ->setEditView(static::ORDERS_EDIT_FORM_VIEW)
                        ->addToolbarActions($formToolbarActions)
                        ->setParent(static::ORDERS_ADD_FORM_VIEW),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createResourceTabViewBuilder(static::ORDERS_EDIT_FORM_VIEW, '/voucher-orders/:id')
                        ->setResourceKey(VoucherOrders::RESOURCE_KEY)
                        ->setBackView(static::ORDERS_LIST_VIEW),
                );

                $viewCollection->add(
                    $this->viewBuilderFactory
                        ->createFormViewBuilder(static::ORDERS_EDIT_FORM_DETAILS_VIEW, '/details')
                        ->setResourceKey(VoucherOrders::RESOURCE_KEY)
                        ->setFormKey(VoucherOrders::FORM_KEY)
                        ->setTabTitle('sulu_admin.details')
                        ->addToolbarActions($formToolbarActions)
                        ->setParent(static::ORDERS_EDIT_FORM_VIEW),
                );
            }
        }
    }

    /**
     * @return mixed[]
     */
    public function getSecurityContexts(): array
    {
        return [
            self::SULU_ADMIN_SECURITY_SYSTEM => [
                'Voucher' => [
                    VoucherOrders::SECURITY_CONTEXT => [
                        PermissionTypes::VIEW,
                        PermissionTypes::ADD,
                        PermissionTypes::EDIT,
                        PermissionTypes::DELETE,
                    ],
                    VoucherCategories::SECURITY_CONTEXT => [
                        PermissionTypes::VIEW,
                        PermissionTypes::ADD,
                        PermissionTypes::EDIT,
                        PermissionTypes::DELETE,
                    ],
                ],
            ],
        ];
    }
}
