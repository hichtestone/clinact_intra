<?php

namespace App\Entity\AuditTrail;

use App\Entity\User;
use App\Repository\AuditTrail\UserAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAuditTrailRepository::class)
 */
class UserAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User
     */
    private $entity;

    /**
     * @return User
     */
    public function getEntity(): User
    {
        return $this->entity;
    }

    /**
     * @param User $entity
     */
    public function setEntity(User $entity): void
    {
        $this->entity = $entity;
    }
}
