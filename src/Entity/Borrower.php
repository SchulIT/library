<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: ['barcodeId'])]
class Borrower {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING, enumType: BorrowerType::class)]
    private BorrowerType $type;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    private string $barcodeId;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $lastname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(max: 255)]
    private string $email;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $grade;

    /**
     * @var Collection<Checkout>
     */
    #[ORM\OneToMany(targetEntity: Checkout::class, mappedBy: 'borrower')]
    private Collection $checkouts;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->checkouts = new ArrayCollection();
    }

    public function getType(): BorrowerType {
        return $this->type;
    }

    public function setType(BorrowerType $type): void {
        $this->type = $type;
    }

    public function getBarcodeId(): string {
        return $this->barcodeId;
    }

    public function setBarcodeId(string $barcodeId): void {
        $this->barcodeId = $barcodeId;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void {
        $this->firstname = $firstname;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void {
        $this->lastname = $lastname;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getGrade(): ?string {
        return $this->grade;
    }

    public function setGrade(?string $grade): void {
        $this->grade = $grade;
    }

    /**
     * @return Collection<Checkout>
     */
    public function getCheckouts(): Collection {
        return $this->checkouts;
    }
}