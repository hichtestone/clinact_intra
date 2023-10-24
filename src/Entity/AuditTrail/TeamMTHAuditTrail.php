<?php

namespace App\Entity\AuditTrail;

use App\Entity\TeamMTH;
use App\Repository\AuditTrail\TeamMTHAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamMTHAuditTrailRepository::class)
 */
class TeamMTHAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\TeamMTH")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var TeamMTH
     */
    private $entity;

    /**
     * @return TeamMTH
     */
    public function getEntity(): TeamMTH
    {
        return $this->entity;
    }

    /**
     * @param TeamMTH $entity
     */
    public function setEntity(TeamMTH $entity): void
    {
        $this->entity = $entity;
    }
}
