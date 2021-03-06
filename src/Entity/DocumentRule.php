<?php

namespace App\Entity;

use App\Repository\DocumentRuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRuleRepository::class)]
class DocumentRule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'datetime_immutable')]
    private $createdAt;

    #[ORM\OneToMany(mappedBy: 'documentRule', targetEntity: User::class)]
    private $User;

    #[ORM\OneToMany(mappedBy: 'document', targetEntity: User::class)]
    private $userRole;

    #[ORM\ManyToOne(targetEntity: Document::class, inversedBy: 'documentRule')]
    #[ORM\JoinColumn(nullable: false)]
    private $document;

    public function __construct()
    {
        $this->User = new ArrayCollection();
        $this->userRole = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->User;
    }

    public function addUser(User $user): self
    {
        if (!$this->User->contains($user)) {
            $this->User[] = $user;
            $user->setDocumentRule($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->User->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getDocumentRule() === $this) {
                $user->setDocumentRule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserRole(): Collection
    {
        return $this->userRole;
    }

    public function addUserRole(User $userRole): self
    {
        if (!$this->userRole->contains($userRole)) {
            $this->userRole[] = $userRole;
            $userRole->setDocument($this);
        }

        return $this;
    }

    public function removeUserRole(User $userRole): self
    {
        if ($this->userRole->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getDocument() === $this) {
                $userRole->setDocument(null);
            }
        }

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }
}
