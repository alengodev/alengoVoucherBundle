<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Entity\Factory;

use Sulu\Bundle\MediaBundle\Entity\Media;

interface MediaFactoryInterface
{
    public function getMedia($object): ?Media;
}
