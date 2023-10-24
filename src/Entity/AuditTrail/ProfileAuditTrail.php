<?php

namespace App\Entity\AuditTrail;

use App\Entity\Profile;
use App\Repository\AuditTrail\ProfileAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProfileAuditTrailRepository::class)
 */
class ProfileAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Profile
     */
    private $entity;

    public function getEntity(): Profile
    {
        return $this->entity;
    }

    public function setEntity(Profile $entity): void
    {
        $this->entity = $entity;
    }
}
