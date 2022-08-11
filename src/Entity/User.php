<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity("email")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[Assert\NotBlank(message: "Please enter a valid email address.")]
    #[Assert\Email()]
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[Assert\NotBlank(message: "Please enter a valid password")]
    #[Assert\Length(max: 4096)]
    #[ORM\Column(type: 'string')]
    private $password;

    #[Assert\NotBlank(message: "Valid first name is required.")]
    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[Assert\NotBlank(message: "Valid last name is required.")]
    #[ORM\Column(type: 'string', length: 255)]
    private $last_name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $vimeo_api_key;

    #[ORM\ManyToMany(targetEntity: Video::class, mappedBy: 'usersThatLike')]
    #[ORM\JoinTable(name: 'likes')]
    private $likedVideos;

    #[ORM\ManyToMany(targetEntity: Video::class, mappedBy: 'usersThatDontLike')]
    #[ORM\JoinTable(name: 'dislikes')]
    private $dislikedVideos;

    #[ORM\OneToOne(targetEntity: Subscription::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $subscription;

    public function __construct()
    {
        $this->likedVideos = new ArrayCollection();
        $this->dislikedVideos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getVimeoApiKey(): ?string
    {
        return $this->vimeo_api_key;
    }

    public function setVimeoApiKey(?string $vimeo_api_key): self
    {
        $this->vimeo_api_key = $vimeo_api_key;

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getLikedVideos(): Collection
    {
        return $this->likedVideos;
    }

    public function addLikedVideo(Video $likedVideo): self
    {
        if (!$this->likedVideos->contains($likedVideo)) {
            $this->likedVideos[] = $likedVideo;
            $likedVideo->addUsersThatLike($this);
        }

        return $this;
    }

    public function removeLikedVideo(Video $likedVideo): self
    {
        if ($this->likedVideos->removeElement($likedVideo)) {
            $likedVideo->removeUsersThatLike($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getDislikedVideos(): Collection
    {
        return $this->dislikedVideos;
    }

    public function addDislikedVideo(Video $dislikedVideo): self
    {
        if (!$this->dislikedVideos->contains($dislikedVideo)) {
            $this->dislikedVideos[] = $dislikedVideo;
            $dislikedVideo->addUsersThatDontLike($this);
        }

        return $this;
    }

    public function removeDislikedVideo(Video $dislikedVideo): self
    {
        if ($this->dislikedVideos->removeElement($dislikedVideo)) {
            $dislikedVideo->removeUsersThatDontLike($this);
        }

        return $this;
    }

    public function getSubscription(): ?Subscription
    {
        return $this->subscription;
    }

    public function setSubscription(?Subscription $subscription): self
    {
        $this->subscription = $subscription;

        return $this;
    }
}
