<?php

namespace App\Entity\AuditTrail;

use App\Entity\Training;
use App\Repository\AuditTrail\TrainingAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrainingAuditTrailRepository::class)
 */
class TrainingAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Training")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Training
     */
    private $entity;

    /**
     * @return Training
     */
    public function getEntity(): Training
    {
        return $this->entity;
    }

    /**
     * @param Training $entity
     */
    public function setEntity(Training $entity): void
    {
        $this->entity = $entity;
    }
}
