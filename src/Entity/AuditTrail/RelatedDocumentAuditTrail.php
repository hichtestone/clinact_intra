<?php

namespace App\Entity\AuditTrail;

use App\Entity\RelatedDocument;
use App\Repository\AuditTrail\RelatedDocumentAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RelatedDocumentAuditTrailRepository::class)
 */
class RelatedDocumentAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\RelatedDocument")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var RelatedDocument
     */
    private $entity;

    /**
     * @return RelatedDocument
     */
    public function getEntity(): RelatedDocument
    {
        return $this->entity;
    }

    /**
     * @param RelatedDocument $entity
     */
    public function setEntity(RelatedDocument $entity): void
    {
        $this->entity = $entity;
    }
}
