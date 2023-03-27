<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Entity;

use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

#[ORM\Entity(repositoryClass: VoucherCategoriesRepository::class)]
class VoucherCategories implements AuditableInterface
{
    use AuditableTrait;
    final public const RESOURCE_KEY = 'voucher_categories';
    final public const FORM_KEY = 'voucher_categories_details';
    final public const FORM_KEY_EXCERPT = 'voucher_categories_excerpt';
    final public const FORM_KEY_SETTINGS = 'voucher_categories_settings';
    final public const LIST_KEY = 'voucher_categories';
    final public const SECURITY_CONTEXT = 'sulu.voucher.categories';
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $webspaceSettings = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $webspaceKey = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true, options: ['default' => 0])]
    private ?float $amount = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $enabled = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $position = null;

    /**
     * @var Collection<VoucherCategoryTranslations>
     */
    #[ORM\OneToMany(targetEntity: VoucherCategoryTranslations::class, mappedBy: 'idCategories')]
    private Collection $voucherCategoryTranslations;

    public function __construct()
    {
        $this->voucherCategoryTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWebspaceSettings(): ?bool
    {
        return $this->webspaceSettings;
    }

    public function setWebspaceSettings(?bool $webspaceSettings): void
    {
        $this->webspaceSettings = $webspaceSettings;
    }

    public function getWebspaceKey(): ?string
    {
        return $this->webspaceKey;
    }

    public function setWebspaceKey(string $webspaceKey): self
    {
        $this->webspaceKey = $webspaceKey;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection|VoucherCategoryTranslations[]
     */
    public function getVoucherCategoryTranslations(): Collection
    {
        return $this->voucherCategoryTranslations;
    }

    public function addVoucherCategoryTranslation(VoucherCategoryTranslations $VoucherCategoryTranslations): self
    {
        if (!$this->voucherCategoryTranslations->contains($VoucherCategoryTranslations)) {
            $this->voucherCategoryTranslations[] = $VoucherCategoryTranslations;
            $VoucherCategoryTranslations->setIdCategories($this);
        }

        return $this;
    }

    public function removeVoucherCategoryTranslation(VoucherCategoryTranslations $VoucherCategoryTranslations): self
    {
        // set the owning side to null (unless already changed)
        if ($this->voucherCategoryTranslations->removeElement($VoucherCategoryTranslations) && $VoucherCategoryTranslations->getIdCategories() === $this) {
            $VoucherCategoryTranslations->setIdCategories(null);
        }

        return $this;
    }
}
