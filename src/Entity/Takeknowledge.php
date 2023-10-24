<?php

namespace App\Entity;

use App\Repository\TakeknowledgeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TakeknowledgeRepository::class)
 */
class Takeknowledge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $signedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="takeknowledges")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=DocumentTransverse::class, inversedBy="takeknowledges")
     */
    private $documentTransverse;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSigneed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSignedAt(): ?\DateTimeInterface
    {
        return $this->signedAt;
    }

    public function setSignedAt(?\DateTimeInterface $signedAt): self
    {
        $this->signedAt = $signedAt;

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

    public function getDocumentTransverse(): ?DocumentTransverse
    {
        return $this->documentTransverse;
    }

    public function setDocumentTransverse(?DocumentTransverse $documentTransverse): self
    {
        $this->documentTransverse = $documentTransverse;

        return $this;
    }

    public function getIsSigneed(): ?bool
    {
        return $this->isSigneed;
    }

    public function setIsSigneed(?bool $isSigneed): self
    {
        $this->isSigneed = $isSigneed;

        return $this;
    }
}
