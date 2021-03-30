<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RequestRepository::class)
 */
class Request
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * @Groups("requests_read")
     * @Groups("beneficiaries_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("beneficiaries_read")
     * @Groups("requests_read")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * 
     * @Assert\NotBlank
     *
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups("beneficiaries_read")
     * @Groups("requests_read")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * 
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("beneficiaries_read")
     * @Groups("requests_read")
     * @Groups("categories_read")
     * @Groups("departments_read")
     */
    private $interventionDate;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("requests_read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("requests_read")
     * @Groups("categories_read")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="requests")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("beneficiaries_read")
     * @Groups("requests_read")
     * @Groups("departments_read")
     * 
     * @Assert\NotBlank
     *
     */
    private $category;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->interventionDate = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getInterventionDate(): ?\DateTimeInterface
    {
        return $this->interventionDate;
    }

    public function setInterventionDate(\DateTimeInterface $interventionDate): self
    {
        $this->interventionDate = $interventionDate;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}
