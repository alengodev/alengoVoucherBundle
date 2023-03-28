<?php

declare(strict_types=1);

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Alengo\Bundle\AlengoVoucherBundle\Helper;

use Sulu\Component\Webspace\Manager\WebspaceManagerInterface;

class WebspaceSelect
{
    public function __construct(private readonly WebspaceManagerInterface $webspaceManager)
    {
    }

    public function getValues(): array
    {
        $values = [];
        foreach ($this->webspaceManager->getWebspaceCollection() as $webspace) {
            $values[] = [
                'name' => $webspace->getKey(),
                'title' => $webspace->getName(),
            ];
        }

        return $values;
    }
}
