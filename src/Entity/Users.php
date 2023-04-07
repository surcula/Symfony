<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @ORM\Table(name="Users")
 * @UniqueEntity(fields={"username"},message="le nom d'utilisateur doit être unique")
 * @method string getUserIdentifier()
 */
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="veuillez encoder un mot de passe",groups="registration")
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@%&]).{8}$/",
     *     htmlPattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@%&]).{8}$",
     *     message="le password doit contenir au moins 1 majuscule, 1 caractère spécial",
     *     groups="registration"
     * )
     * @Assert\Length(max=8,min=8,exactMessage="la longeur doit être de 8 caractères",
     *     groups="registration")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Email(message="l'email doit être valide")
     */
    private $mail;

    /**
     * @ORM\ManyToOne(targetEntity=Roles::class, inversedBy="users")
     */
    private $id_role;

    /**
     * @Assert\EqualTo(propertyPath="password",
     *     message="les mots de passe ne sont pas identiques",
     *     groups="registration")
     */
    private $confirm_password;

    /**
     * @return mixed
     */
    public function getConfirmPassword()
    {
        return $this->confirm_password;
    }

    /**
     * @param mixed $confirm_password
     */
    public function setConfirmPassword($confirm_password): void
    {
        $this->confirm_password = $confirm_password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = password_hash($password,PASSWORD_BCRYPT);

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getIdRole(): ?Roles
    {
        return $this->id_role;
    }

    public function setIdRole(?Roles $id_role): self
    {
        $this->id_role = $id_role;

        return $this;
    }

    public function getRoles(): ?array
    {
        return array('Role_'.strtoupper($this->id_role()));
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}
