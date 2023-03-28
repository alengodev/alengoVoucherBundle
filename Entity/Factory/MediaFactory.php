<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Entity\Factory;

use Sulu\Bundle\MediaBundle\Entity\Media;
use Sulu\Bundle\MediaBundle\Entity\MediaRepositoryInterface;

class MediaFactory implements MediaFactoryInterface
{
    public function __construct(
        private readonly MediaRepositoryInterface $mediaRepository,
    ) {
    }

    public function getMedia($object): ?Media
    {
        $mediaEntity = null;

        if ($object instanceof Media) {
            $mediaEntity = $this->mediaRepository->findMediaById($object->getId());
        }

        return $mediaEntity;
    }
}
