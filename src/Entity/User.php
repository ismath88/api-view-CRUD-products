<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * A user.
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"user_read"}},
 *     "denormalization_context"={"groups"={"user_write"}},
 *     "filters"={
 *         "user.order",
 *         "user.search",
 *     },
 * })
 */
class User implements UserInterface
{
    use Traits\BlameableTrait;
    use Traits\TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string Username.
     *
     * @ORM\Column(type="string", length=50, unique=true, nullable=false)
     * @ApiProperty()
     */
    protected $username;

    /**
     * @var Company|null Company name reference to Company table code column
     *
     * @ORM\ManyToOne(targetEntity="Company")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     * @ApiProperty()
     */
    private $company;

    /**
     * @var string firstname.
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     * @ApiProperty()
     */
    protected $firstname;

    /**
     * @var string lastname.
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     * @ApiProperty()
     */
    protected $lastname;

    /**
     * @var string mobile.
     *
     * @ORM\Column(type="string", length=20, nullable=false)
     * @ApiProperty()
     */
    protected $mobile;

    /**
     * @var \DateTime The date when the calendar item becomes valid.
     *
     * @ORM\Column(type="datetime", nullable=false)
     * @ApiProperty(iri="http://schema.org/validFrom")
     */
    private $validFrom;

    /**
     * @var string status.
     *
     * @ORM\Column(type="string", length=10, nullable=false)
     * @ApiProperty()
     */
    protected $status;

    /**
     * @var string The user's password.
     *
     * @ORM\Column(type="text", nullable=false)
     * @ApiProperty()
     */
    protected $password;

    /**
     * @var \DateTime|null The date when the user was activated.
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty()
     */
    protected $dateActivated;

    /**
     * @var \DateTime|null The last time user was logged on.
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty()
     */
    protected $dateLastLogon;

    /**
     * @var string|null
     *
     * @ApiProperty()
     */
    protected $plainPassword;

    /**
     * @var bool mobileUser
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @ApiProperty()
     */
    private $mobileUser;

    /**
     * @var bool webUser
     *
     * @ORM\Column(type="boolean", nullable=false)
     * @ApiProperty()
     */
    private $webUser;

    /**
     * Gets id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets username.
     *
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Gets username.
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Gets Company.
     *
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * Sets Company.
     *
     * @param Company|null $company
     *
     * @return self
     */
    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Sets firstname.
     *
     * @param string $firstname
     *
     * @return $this
     */
    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Gets firstname.
     *
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * Sets lastname.
     *
     * @param string $lastname
     *
     * @return $this
     */
    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Gets lastname.
     *
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * Sets mobile.
     *
     * @param string $mobile
     *
     * @return $this
     */
    public function setMobile(string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Gets mobile.
     *
     * @return string
     */
    public function getMobile(): string
    {
        return $this->mobile;
    }

    /**
     * Gets validFrom.
     *
     * @return \DateTime
     */
    public function getValidFrom(): \DateTime
    {
        return $this->validFrom;
    }

    /**
     * Sets validFrom.
     *
     * @param \DateTime $validFrom
     *
     * @return self
     */
    public function setValidFrom(\DateTime $validFrom): self
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * Gets status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Sets status.
     *
     * @param string $status
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Sets password.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Gets password.
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Sets dateActivated.
     *
     * @param \DateTime|null $dateActivated
     *
     * @return $this
     */
    public function setDateActivated(?\DateTime $dateActivated)
    {
        $this->dateActivated = $dateActivated;

        return $this;
    }

    /**
     * Gets dateActivated.
     *
     * @return \DateTime|null
     */
    public function getDateActivated(): ?\DateTime
    {
        return $this->dateActivated;
    }

    /**
     * Sets dateLastLogon.
     *
     * @param \DateTime|null $dateLastLogon
     *
     * @return $this
     */
    public function setDateLastLogon(?\DateTime $dateLastLogon)
    {
        $this->dateLastLogon = $dateLastLogon;

        return $this;
    }

    /**
     * Gets dateLastLogon.
     *
     * @return \DateTime|null
     */
    public function getDateLastLogon(): ?\DateTime
    {
        return $this->dateLastLogon;
    }

    /**
     * Sets plainPassword.
     *
     * @param string|null $plainPassword
     *
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Gets plainPassword.
     *
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Sets mobileUser.
     *
     * @param bool $mobileUser
     *
     * @return self
     */
    public function setMobileUser(bool $mobileUser): self
    {
        $this->mobileUser = $mobileUser;

        return $this;
    }

    /**
     * Gets mobileUser.
     *
     * @return bool
     */
    public function getMobileUser(): bool
    {
        return $this->mobileUser;
    }

    /**
     * Sets webUser.
     *
     * @param bool $webUser
     *
     * @return self
     */
    public function setWebUser(bool $webUser): self
    {
        $this->webUser = $webUser;

        return $this;
    }

    /**
     * Gets webUser.
     *
     * @return bool
     */
    public function getWebUser(): bool
    {
        return $this->webUser;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }
}
