<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Entity;

use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherOrderNumberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoucherOrderNumberRepository::class)]
class VoucherOrderNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $consecutiveNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsecutiveNumber(): ?int
    {
        return $this->consecutiveNumber;
    }

    public function setConsecutiveNumber(?int $consecutiveNumber): self
    {
        $this->consecutiveNumber = $consecutiveNumber;

        return $this;
    }
}
