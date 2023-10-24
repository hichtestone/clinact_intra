<?php

namespace App\Entity\AuditTrail;

use App\Entity\DocumentTransverse;
use App\Entity\VersionDocumentTransverse;
use App\Repository\AuditTrail\VersionDocumentTransverseAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VersionDocumentTransverseAuditTrailRepository::class)
 */
class VersionDocumentTransverseAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\VersionDocumentTransverse")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var VersionDocumentTransverse
     */
    private $entity;

    /**
     * @return VersionDocumentTransverse
     */
    public function getEntity(): VersionDocumentTransverse
    {
        return $this->entity;
    }

    /**
     * @param VersionDocumentTransverse $entity
     */
    public function setEntity(VersionDocumentTransverse $entity): void
    {
        $this->entity = $entity;
    }
}
