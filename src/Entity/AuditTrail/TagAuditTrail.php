<?php

namespace App\Entity\AuditTrail;

use App\Entity\Tag;
use App\Repository\AuditTrail\TagAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagAuditTrailRepository::class)
 */
class TagAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Tag")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Tag
     */
    private $entity;

    /**
     * @return Tag
     */
    public function getEntity(): Tag
    {
        return $this->entity;
    }

    /**
     * @param Tag $entity
     */
    public function setEntity(Tag $entity): void
    {
        $this->entity = $entity;
    }
}
