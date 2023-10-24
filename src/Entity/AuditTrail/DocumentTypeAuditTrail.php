<?php

namespace App\Entity\AuditTrail;

use App\Entity\DocumentType;
use App\Repository\AuditTrail\DocumentTypeAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentTypeAuditTrailRepository::class)
 */
class DocumentTypeAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\DocumentType")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var DocumentType
     */
    private $entity;

    /**
     * @return DocumentType
     */
    public function getEntity(): DocumentType
    {
        return $this->entity;
    }

    /**
     * @param DocumentType $entity
     */
    public function setEntity(DocumentType $entity): void
    {
        $this->entity = $entity;
    }
}
