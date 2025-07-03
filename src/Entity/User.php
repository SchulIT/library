<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity(fields: 'username')]
#[UniqueEntity(fields: 'idpIdentifier')]
class User implements UserInterface {
    use IdTrait;
    use UuidTrait;

    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $idpIdentifier;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private string $username;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $firstname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private string $lastname;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email;

    #[ORM\Column(type: Types::JSON)]
    private array $roles = ['ROLE_USER'];

    public function __construct() {
        $this->uuid = Uuid::v4()->toString();
    }

    public function getIdpIdentifier(): Uuid {
        return $this->idpIdentifier;
    }

    public function setIdpIdentifier(Uuid $idpIdentifier): User {
        $this->idpIdentifier = $idpIdentifier;
        return $this;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function setUsername(string $username): User {
        $this->username = $username;
        return $this;
    }

    public function getFirstname(): string {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): User {
        $this->firstname = $firstname;
        return $this;
    }

    public function getLastname(): string {
        return $this->lastname;
    }

    public function setLastname(string $lastname): User {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): User {
        $this->email = $email;
        return $this;
    }

    public function setRoles(array $roles): User {
        $this->roles = $roles;
        return $this;
    }

    public function getRoles(): array {
        return $this->roles;
    }

    public function eraseCredentials(): void { }

    public function getUserIdentifier(): string {
        return $this->username;
    }

    public function __toString(): string {
        return $this->username;
    }
}