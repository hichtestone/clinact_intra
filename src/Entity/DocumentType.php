<?php

namespace App\Entity;

use App\Repository\DocumentTypeRepository;
use App\Service\AuditTrail\AuditrailableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentTypeRepository::class)
 */
class DocumentType implements AuditrailableInterface
{
    public const NAME_GENERAL  = 1;
    public const NAME_QUALITE = 2;
    public const NAME     = [
        self::NAME_GENERAL  => 'GENERALE',
        self::NAME_QUALITE => 'QUALITE',
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity=DocumentTransverse::class, mappedBy="documentType")
     */
    private $documentTransverses;

    public function __construct()
    {
        $this->documentTransverses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        if (!array_key_exists($name, self::NAME) && $name !== null) {
            throw new \Exception('ce type' .$name. 'n\'est pas authorisé!');
        }

        $this->name = $name;

    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return Collection|DocumentTransverse[]
     */
    public function getDocumentTransverses(): Collection
    {
        return $this->documentTransverses;
    }

    public function addDocumentTransverse(DocumentTransverse $documentTransverse): self
    {
        if (!$this->documentTransverses->contains($documentTransverse)) {
            $this->documentTransverses[] = $documentTransverse;
            $documentTransverse->setDocumentType($this);
        }

        return $this;
    }

    public function removeDocumentTransverse(DocumentTransverse $documentTransverse): self
    {
        if ($this->documentTransverses->removeElement($documentTransverse)) {
            // set the owning side to null (unless already changed)
            if ($documentTransverse->getDocumentType() === $this) {
                $documentTransverse->setDocumentType(null);
            }
        }

        return $this;
    }

    public function getFieldsToBeIgnored(): array
    {
       return  [''];
    }
}
