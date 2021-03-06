<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\ManyToOne(targetEntity: DocumentType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $DocumentType;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DocumentRule::class)]
    private $documentRule;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'documents')]
    #[ORM\JoinColumn(nullable: false)]
    private $user;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DocumentMedia::class, orphanRemoval: true)]
    private $media;

    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->documentRule = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getDocumentType(): ?DocumentType
    {
        return $this->DocumentType;
    }

    public function setDocumentType(?DocumentType $DocumentType): self
    {
        $this->DocumentType = $DocumentType;

        return $this;
    }


    /**
     * @return Collection<int, DocumentRule>
     */
    public function getDocumentRule(): Collection
    {
        return $this->documentRule;
    }

    public function addDocumentRule(DocumentRule $documentRule): self
    {
        if (!$this->documentRule->contains($documentRule)) {
            $this->documentRule[] = $documentRule;
            $documentRule->setDocument($this);
        }

        return $this;
    }

    public function removeDocumentRule(DocumentRule $documentRule): self
    {
        if ($this->documentRule->removeElement($documentRule)) {
            // set the owning side to null (unless already changed)
            if ($documentRule->getDocument() === $this) {
                $documentRule->setDocument(null);
            }
        }

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

    /**
     * @return Collection<int, DocumentMedia>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(DocumentMedia $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media[] = $medium;
            $medium->setDocument($this);
        }

        return $this;
    }

    public function removeMedium(DocumentMedia $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getDocument() === $this) {
                $medium->setDocument(null);
            }
        }

        return $this;
    }
}
