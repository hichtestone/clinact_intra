<?php

namespace App\Entity\AuditTrail;

use App\Entity\DocumentTransverse;
use App\Repository\AuditTrail\DocumentTransverseAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentTransverseAuditTrailRepository::class)
 */
class DocumentTransverseAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\DocumentTransverse")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var DocumentTransverse
     */
    private $entity;

    /**
     * @return DocumentTransverse
     */
    public function getEntity(): DocumentTransverse
    {
        return $this->entity;
    }

    /**
     * @param DocumentTransverse $entity
     */
    public function setEntity(DocumentTransverse $entity): void
    {
        $this->entity = $entity;
    }
}
