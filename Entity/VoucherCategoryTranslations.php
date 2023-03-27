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

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(targetEntity: MediaInterface::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?MediaInterface $previewImage = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $voucherDescription = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $voucherHeadline = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $voucherText = null;

    #[ORM\ManyToOne(targetEntity: MediaInterface::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?MediaInterface $voucherImage = null;

    #[ORM\ManyToOne(targetEntity: MediaInterface::class)]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?MediaInterface $voucherBackgroundImage = null;

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

    public function getVoucherDescription(): ?string
    {
        return $this->voucherDescription;
    }

    public function setVoucherDescription(?string $voucherDescription): void
    {
        $this->voucherDescription = $voucherDescription;
    }

    public function getVoucherHeadline(): ?string
    {
        return $this->voucherHeadline;
    }

    public function setVoucherHeadline(?string $voucherHeadline): void
    {
        $this->voucherHeadline = $voucherHeadline;
    }

    public function getVoucherText(): ?string
    {
        return $this->voucherText;
    }

    public function setVoucherText(?string $voucherText): void
    {
        $this->voucherText = $voucherText;
    }

    public function getVoucherImage(): ?MediaInterface
    {
        return $this->voucherImage;
    }

    public function setVoucherImage(?MediaInterface $voucherImage): void
    {
        $this->voucherImage = $voucherImage;
    }

    public function getVoucherBackgroundImage(): ?MediaInterface
    {
        return $this->voucherBackgroundImage;
    }

    public function setVoucherBackgroundImage(?MediaInterface $voucherBackgroundImage): void
    {
        $this->voucherBackgroundImage = $voucherBackgroundImage;
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
