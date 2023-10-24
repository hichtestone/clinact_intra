<?php

namespace App\Entity\AuditTrail;

use App\Entity\Video;
use App\Repository\AuditTrail\VideoAuditTrailRepository;
use App\Service\AuditTrail\AbstractAuditTrailEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VideoAuditTrailRepository::class)
 */
class VideoAuditTrail extends AbstractAuditTrailEntity
{
    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\Video")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var Video
     */
    private $entity;

    /**
     * @return Video
     */
    public function getEntity(): Video
    {
        return $this->entity;
    }

    /**
     * @param Video $entity
     */
    public function setEntity(Video $entity): void
    {
        $this->entity = $entity;
    }
}
