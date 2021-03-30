<?php

namespace App\Entity;

use App\Repository\PropositionRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PropositionRepository::class)
 */
class Proposition
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * @Groups("propositions_read")
     * @Groups("volunteers_read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("volunteers_read")
     * @Groups("propositions_read")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * 
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Groups("volunteers_read")
     * @Groups("propositions_read")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * 
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("propositions_read")
     * @Groups("categories_read")
     * @Groups("departments_read")
     * @Groups("volunteers_read")
     * 
     */
    private $disponibilityDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="propositions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("propositions_read")
     * @Groups("categories_read")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="propositions")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("volunteers_read")
     * @Groups("propositions_read")
     * @Groups("departments_read")
     * 
     * @Assert\NotBlank
     */
    private $category;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->disponibilityDate = new \DateTime();
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

    public function getDisponibilityDate(): ?\DateTimeInterface
    {
        return $this->disponibilityDate;
    }

    public function setDisponibilityDate(\DateTimeInterface $disponibilityDate): self
    {
        $this->disponibilityDate = $disponibilityDate;

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
