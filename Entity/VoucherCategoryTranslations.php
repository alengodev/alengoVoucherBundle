<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Entity;

use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherCategoryTranslationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Bundle\MediaBundle\Entity\MediaInterface;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

#[ORM\Entity(repositoryClass: VoucherCategoryTranslationsRepository::class)]
#[ORM\Table]
#[ORM\UniqueConstraint(name: 'assignment_unique', columns: ['id_categories_id', 'locale'])]
class VoucherCategoryTranslations implements AuditableInterface, \Stringable
{
    use AuditableTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $locale = null;

    #[ORM\Column(type: Types::JSON)]
    private ?array $data = [];

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: MediaInterface::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?MediaInterface $previewImage = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $countedVouchers = 0;

    #[ORM\ManyToOne(targetEntity: VoucherCategories::class, inversedBy: 'voucherCategoryTranslations')]
    private ?VoucherCategories $idCategories = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocale(): ?string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPreviewImage(): ?MediaInterface
    {
        return $this->previewImage;
    }

    public function setPreviewImage(?MediaInterface $previewImage): void
    {
        $this->previewImage = $previewImage;
    }

    public function getCountedVouchers(): ?int
    {
        return $this->countedVouchers;
    }

    public function setCountedVouchers(?int $countedVouchers): void
    {
        $this->countedVouchers = $countedVouchers;
    }

    public function getIdCategories(): ?VoucherCategories
    {
        return $this->idCategories;
    }

    public function setIdCategories(?VoucherCategories $idCategories): self
    {
        $this->idCategories = $idCategories;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}
