<?php

namespace App\Entity\AuditTrail;

use App\Entity\PresedentWord;
use App\Repository\AuditTrail\PresedentWordAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PresedentWordAuditTrailRepository::class)
 */
class PresedentWordAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\PresedentWord")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var PresedentWord
     */
    private $entity;

    /**
     * @return PresedentWord
     */
    public function getEntity(): PresedentWord
    {
        return $this->entity;
    }

    /**
     * @param PresedentWord $entity
     */
    public function setEntity(PresedentWord $entity): void
    {
        $this->entity = $entity;
    }
}
