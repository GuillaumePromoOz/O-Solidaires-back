<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * @Groups("propositions_read")
     * @Groups("requests_read")
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("admins_read")
     * 
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("departments_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     * 
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("departments_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     * 
     * @Assert\NotBlank
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(min = 8)
     * 
     * - au moins une lettre minuscule
     * - au moins une lettre majuscule
     * - au moins un chiffre
     * @Assert\Regex("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/")  
     * @Assert\NotBlank
     * 
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("departments_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     * 
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("departments_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     * 
     * @Assert\NotBlank
     * @Assert\Type("string")
     */
    private $firstname;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("departments_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     */
    private $picture;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Department::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     * 
     * @Assert\NotBlank
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity=Proposition::class, mappedBy="user", cascade={"remove"})
     * @Groups("volunteers_read")
     * @Groups("departments_read")
     */
    private $propositions;

    /**
     * @ORM\OneToMany(targetEntity=Request::class, mappedBy="user", cascade={"remove"})
     * @Groups("beneficiaries_read")
     * @Groups("departments_read")
     */
    private $requests;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     */
    private $bio;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * 
     * @Groups("beneficiaries_read")
     * @Groups("volunteers_read")
     * @Groups("requests_read")
     * @Groups("propositions_read")
     * @Groups("categories_read")
     * @Groups("admins_read")
     * @Assert\Regex("/^[0-9]{10}$/")  
     * 
     */
    private $phoneNumber;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->propositions = new ArrayCollection();
        $this->requests = new ArrayCollection();
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
    public function getUsername(): string
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
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function setPictureFilename($pictureFilename)
    {
        $this->pictureFilename = $pictureFilename;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositions(): Collection
    {
        return $this->propositions;
    }

    public function addProposition(Proposition $proposition): self
    {
        if (!$this->propositions->contains($proposition)) {
            $this->propositions[] = $proposition;
            $proposition->setUser($this);
        }

        return $this;
    }

    public function removeProposition(Proposition $proposition): self
    {
        if ($this->propositions->removeElement($proposition)) {
            // set the owning side to null (unless already changed)
            if ($proposition->getUser() === $this) {
                $proposition->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Request[]
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): self
    {
        if (!$this->requests->contains($request)) {
            $this->requests[] = $request;
            $request->setUser($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): self
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getUser() === $this) {
                $request->setUser(null);
            }
        }

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function __toString()
    {

        return $this->firstname . ' ' . $this->lastname;
    }
}
