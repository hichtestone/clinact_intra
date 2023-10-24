<?php

namespace App\Entity;

use App\Repository\DocumentTransverseRepository;
use App\Service\AuditTrail\AuditrailableInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=DocumentTransverseRepository::class)
 * @Vich\Uploadable
 */
class DocumentTransverse implements AuditrailableInterface
{
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
    private $filename;

    /**
     * @var File
     * @Vich\UploadableField (mapping="document_transverse", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validStartAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validEndAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isValid;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=DocumentType::class, inversedBy="documentTransverses")
     */
    private $documentType;

    /**
     * @ORM\OneToMany(targetEntity=VersionDocumentTransverse::class, mappedBy="documentTransverse")
     */
    private $versionDocumentTransverses;

    /**
     * @ORM\OneToMany(targetEntity=RelatedDocument::class, mappedBy="documentTransverse")
     */
    private $relatedDocuments;

    /**
     * @ORM\OneToMany(targetEntity=Training::class, mappedBy="documentTansverse")
     */
    private $trainings;

    /**
     * @ORM\OneToMany(targetEntity=Takeknowledge::class, mappedBy="documentTransverse")
     */
    private $takeknowledges;

    public function __construct()
    {
        $this->versionDocumentTransverses = new ArrayCollection();
        $this->relatedDocuments = new ArrayCollection();
        $this->trainings = new ArrayCollection();
        $this->takeknowledges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): DocumentTransverse
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof UploadedFile){
            $this->updatedAt = new \DateTime();
        }

        return $this;
    }



    public function getValidStartAt(): ?\DateTimeInterface
    {
        return $this->validStartAt;
    }

    public function setValidStartAt(?\DateTimeInterface $validStartAt): self
    {
        $this->validStartAt = $validStartAt;

        return $this;
    }

    public function getValidEndAt(): ?\DateTimeInterface
    {
        return $this->validEndAt;
    }

    public function setValidEndAt(?\DateTimeInterface $validEndAt): self
    {
        $this->validEndAt = $validEndAt;

        return $this;
    }

    public function getIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
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

    public function getDocumentType(): ?DocumentType
    {
        return $this->documentType;
    }

    public function setDocumentType(?DocumentType $documentType): self
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * @return Collection|VersionDocumentTransverse[]
     */
    public function getVersionDocumentTransverses(): Collection
    {
        return $this->versionDocumentTransverses;
    }

    public function addVersionDocumentTransverse(VersionDocumentTransverse $versionDocumentTransverse): self
    {
        if (!$this->versionDocumentTransverses->contains($versionDocumentTransverse)) {
            $this->versionDocumentTransverses[] = $versionDocumentTransverse;
            $versionDocumentTransverse->setDocumentTransverse($this);
        }

        return $this;
    }

    public function removeVersionDocumentTransverse(VersionDocumentTransverse $versionDocumentTransverse): self
    {
        if ($this->versionDocumentTransverses->removeElement($versionDocumentTransverse)) {
            // set the owning side to null (unless already changed)
            if ($versionDocumentTransverse->getDocumentTransverse() === $this) {
                $versionDocumentTransverse->setDocumentTransverse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|RelatedDocument[]
     */
    public function getRelatedDocuments(): Collection
    {
        return $this->relatedDocuments;
    }

    public function addRelatedDocument(RelatedDocument $relatedDocument): self
    {
        if (!$this->relatedDocuments->contains($relatedDocument)) {
            $this->relatedDocuments[] = $relatedDocument;
            $relatedDocument->setDocumentTransverse($this);
        }

        return $this;
    }

    public function removeRelatedDocument(RelatedDocument $relatedDocument): self
    {
        if ($this->relatedDocuments->removeElement($relatedDocument)) {
            // set the owning side to null (unless already changed)
            if ($relatedDocument->getDocumentTransverse() === $this) {
                $relatedDocument->setDocumentTransverse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Training[]
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): self
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings[] = $training;
            $training->setDocumentTansverse($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): self
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getDocumentTansverse() === $this) {
                $training->setDocumentTansverse(null);
            }
        }

        return $this;
    }

    public function getFieldsToBeIgnored(): array
    {
        return ['documentType'];
    }

    /**
     * @return Collection|Takeknowledge[]
     */
    public function getTakeknowledges(): Collection
    {
        return $this->takeknowledges;
    }

    public function addTakeknowledge(Takeknowledge $takeknowledge): self
    {
        if (!$this->takeknowledges->contains($takeknowledge)) {
            $this->takeknowledges[] = $takeknowledge;
            $takeknowledge->setDocumentTransverse($this);
        }

        return $this;
    }

    public function removeTakeknowledge(Takeknowledge $takeknowledge): self
    {
        if ($this->takeknowledges->removeElement($takeknowledge)) {
            // set the owning side to null (unless already changed)
            if ($takeknowledge->getDocumentTransverse() === $this) {
                $takeknowledge->setDocumentTransverse(null);
            }
        }

        return $this;
    }
}
