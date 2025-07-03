<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class Book {

    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $title;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $subtitle = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $barcodeTitle = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank(allowNull: true)]
    #[Assert\Length(max: 255)]
    private ?string $publisher = null;

    #[ORM\Column(type: Types::STRING, length: 17)]
    #[Assert\NotBlank]
    #[Assert\Isbn(type: Assert\Isbn::ISBN_13)]
    private string $isbn;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotBlank(allowNull: true)]
    private string|null $comment;

    #[Vich\UploadableField(mapping: 'covers', fileNameProperty: 'coverFileName')]
    private ?File $cover = null;

    #[ORM\Column(nullable: true)]
    private ?string $coverFileName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    ##[Gedmo\Timestampable(on: 'update')]
    private ?DateTime $updatedAt = null;

    /**
     * @var Collection<BookCopy>
     */
    #[ORM\OneToMany(targetEntity: BookCopy::class, mappedBy: 'book')]
    private Collection $copies;

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
        $this->copies = new ArrayCollection();
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
    }

    public function getSubtitle(): ?string {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): void {
        $this->subtitle = $subtitle;
    }

    public function getBarcodeTitle(): ?string {
        return $this->barcodeTitle;
    }

    public function setBarcodeTitle(?string $barcodeTitle): Book {
        $this->barcodeTitle = $barcodeTitle;
        return $this;
    }

    public function getPublisher(): ?string {
        return $this->publisher;
    }

    public function setPublisher(?string $publisher): void {
        $this->publisher = $publisher;
    }

    public function getIsbn(): string {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): void {
        $this->isbn = $isbn;
    }

    public function getComment(): ?string {
        return $this->comment;
    }

    public function setComment(?string $comment): void {
        $this->comment = $comment;
    }

    public function getCopies(): Collection {
        return $this->copies;
    }

    public function addCopy(BookCopy $copy): void {
        $copy->setBook($this);
        $this->copies->add($copy);
    }

    public function removeCopy(BookCopy $copy): void {
        $this->copies->removeElement($copy);
    }

    public function getCover(): ?File {
        return $this->cover;
    }

    public function hasCover(): bool {
        return !empty($this->coverFileName);
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $cover
     * @return Book
     */
    public function setCover(?File $cover): Book {
        $this->cover = $cover;

        if (null !== $cover) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new DateTime();
        }

        return $this;
    }

    public function getCoverFileName(): ?string {
        return $this->coverFileName;
    }

    public function setCoverFileName(?string $coverFileName): Book {
        $this->coverFileName = $coverFileName;
        return $this;
    }
}