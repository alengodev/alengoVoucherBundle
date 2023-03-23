<?php

declare(strict_types=1);

namespace Alengo\Bundle\AlengoVoucherBundle\Entity;

use Alengo\Bundle\AlengoVoucherBundle\Repository\VoucherOrdersRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sulu\Component\Persistence\Model\AuditableInterface;
use Sulu\Component\Persistence\Model\AuditableTrait;

#[ORM\Entity(repositoryClass: VoucherOrdersRepository::class)]
class VoucherOrders implements AuditableInterface
{
    use AuditableTrait;

    final public const RESOURCE_KEY = 'voucher_orders';
    final public const FORM_KEY = 'voucher_orders_details';
    final public const LIST_KEY = 'voucher_orders';
    final public const SECURITY_CONTEXT = 'sulu.voucher.orders';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: VoucherCategories::class, inversedBy: 'voucherCategoryTranslations')]
    private ?VoucherCategories $idCategories = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $orderNumber = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $voucherAmount = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $voucherHeader = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $voucherText = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $street = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $zip = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::JSON)]
    private array $paymentResponse = [];

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $paymentStatus = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $generatedVoucher = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $redeemed = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $redeemedName = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getVoucherAmount(): ?float
    {
        return $this->voucherAmount;
    }

    public function setVoucherAmount(?float $voucherAmount): self
    {
        $this->voucherAmount = $voucherAmount;

        return $this;
    }

    public function getVoucherHeader(): ?string
    {
        return $this->voucherHeader;
    }

    public function setVoucherHeader(string $voucherHeader): self
    {
        $this->voucherHeader = $voucherHeader;

        return $this;
    }

    public function getVoucherText(): ?string
    {
        return $this->voucherText;
    }

    public function setVoucherText(string $voucherText): self
    {
        $this->voucherText = $voucherText;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getpaymentResponse(): ?array
    {
        return $this->paymentResponse;
    }

    public function setpaymentResponse(array $paymentResponse): self
    {
        $this->paymentResponse = $paymentResponse;

        return $this;
    }

    public function getpaymentStatus(): ?string
    {
        return $this->paymentStatus;
    }

    public function setpaymentStatus(string $paymentStatus): self
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    public function getGeneratedVoucher(): ?string
    {
        return $this->generatedVoucher;
    }

    public function setGeneratedVoucher(string $generatedVoucher): self
    {
        $this->generatedVoucher = $generatedVoucher;

        return $this;
    }

    public function getRedeemed(): ?\DateTimeInterface
    {
        return $this->redeemed;
    }

    public function setRedeemed(\DateTimeInterface $redeemed): self
    {
        $this->redeemed = $redeemed;

        return $this;
    }

    public function getRedeemedName(): ?string
    {
        return $this->redeemedName;
    }

    public function setRedeemedName(string $redeemedName): self
    {
        $this->redeemedName = $redeemedName;

        return $this;
    }
}
