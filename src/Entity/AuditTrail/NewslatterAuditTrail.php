<?php

namespace App\Entity\AuditTrail;

use App\Entity\Newslatter;
use App\Repository\AuditTrail\NewslatterAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NewslatterAuditTrailRepository::class)
 */
class NewslatterAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Newslatter")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Newslatter
     */
    private $entity;

    /**
     * @return Newslatter
     */
    public function getEntity(): Newslatter
    {
        return $this->entity;
    }

    /**
     * @param Newslatter $entity
     */
    public function setEntity(Newslatter $entity): void
    {
        $this->entity = $entity;
    }
}
