<?php

namespace App\Service\AuditTrail;

use App\Repository\UserRepository;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class DoctrineSubscriber
 * @package App\Service\AuditTrail
 */
class DoctrineSubscriber implements EventSubscriber
{
    /**
     * @var UserInterface
     */
    private $activeUser;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var AuditTrailService
     */
    private $auditTrailService;

    private $userrep;

    /**
     * DoctrineSubscriber constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param AuditTrailService $auditTrailService
     */
    public function __construct(TokenStorageInterface $tokenStorage, AuditTrailService $auditTrailService, RequestStack $requestStack, UserRepository $userRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->auditTrailService = $auditTrailService;
        $this->requestStack = $requestStack;
        $this->userrep = $userRepository;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    /**
     * @param mixed $change
     *
     * @throws AuditTrailException
     */
    private function getChangeString($change): string
    {
        if (is_object($change)) {
            if (method_exists($change, 'getAuditTrailString')) {
                /*
                 * @var AuditrailableInterface
                 */
                return $change->getAuditTrailString().' ('.$change->getId().')';
            }
            if ($change instanceof DateTime) {
                return $change->format('Y-m-d H:i:s');
            }
            if (method_exists($change, '__toString')) {
                return (string) $change.' ('.$change->getId().')';
            }
            throw new AuditTrailException('Audit trail error: string conversion impossible for object '.get_class($change));
        } elseif (is_array($change)) {
            return implode(',', $change);
        }

        return $change ?? '';
    }

    /**
     * @param $entity
     * @return AbstractAuditTrailEntity|null
     */
    private function getAuditTrailEntity($entity): ?AbstractAuditTrailEntity
    {
        $auditTrailEntityClass = str_replace('\\Entity\\', '\\Entity\\AuditTrail\\', get_class($entity)).'AuditTrail';
        if (class_exists($auditTrailEntityClass)) {
            /**
             * @var AbstractAuditTrailEntity
             */
            $auditTrailEntity = new $auditTrailEntityClass();

            if($this->requestStack->getCurrentRequest()!= null){

                if (null !== $this->requestStack->getCurrentRequest()->getSession()) {
                    $email = $this->requestStack->getCurrentRequest()->getSession()->get('_security.last_username');

                    if($email==null){
                        $email=$this->requestStack->getCurrentRequest()->getSession()->get('email');
                    }

                    $auditTrailEntity->setUser($this->userrep->findOneBy(['email' =>$email]));
                }
            }
            else{
                $auditTrailEntity->setUser(null);
            }
            $auditTrailEntity->setDate(new DateTime());
            $auditTrailEntity->setReason('todo');
            $auditTrailEntity->setEntity($entity);
            $auditTrailEntity->setReason($this->auditTrailService->getReason());

            return $auditTrailEntity;
        }

        return null;
    }

    /**
     * @param UnitOfWork $uow
     * @param $entity
     * @return AbstractAuditTrailEntity|null
     * @throws AuditTrailException
     */
    private function auditTrailInsert(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        if ($auditTrailEntity = $this->getAuditTrailEntity($entity)) {
            $auditTrailEntity->setModifType(1);

            // filtre changeSet
            $changeSet = $uow->getEntityChangeSet($entity);
            $details = [];
            foreach ($changeSet as $property => $change) {
                if (!in_array($property, $entity->getFieldsToBeIgnored(), true)) {
                    if (null !== $change[1]) {
                        $details[$property] = $this->getChangeString($change[1]);
                    }
                }
            }

            $auditTrailEntity->setDetails($details);

            return $auditTrailEntity;
        }

        return null;
    }

    /**
     * @param UnitOfWork $uow
     * @param $entity
     * @return AbstractAuditTrailEntity|null
     * @throws AuditTrailException
     */
    private function auditTrailUpdate(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        if ($auditTrailEntity = $this->getAuditTrailEntity($entity)) {
            $auditTrailEntity->setModifType(2);

            // filtre changeSet
            $changeSet = $uow->getEntityChangeSet($entity);
            $details = [];
            foreach ($changeSet as $property => $change) {
                if (!in_array($property, $entity->getFieldsToBeIgnored(), true)) {
                    $before = $this->getChangeString($change[0]);
                    $after = $this->getChangeString($change[1]);
                    $details[$property] = [$before, $after];
                }
            }
            if (!empty($details)) {
                $auditTrailEntity->setDetails($details);

                return $auditTrailEntity;
            }
        }

        return null;
    }

    /**
     * @param UnitOfWork $uow
     * @param $entity
     * @return AbstractAuditTrailEntity|null
     */
    private function auditTrailDelete(UnitOfWork $uow, $entity): ?AbstractAuditTrailEntity
    {
        echo 'Impossible de supprimer';
        exit();
    }

    /**
     * @param OnFlushEventArgs $args
     * @throws AuditTrailException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $toBePersisted = [];

        if (null !== $this->tokenStorage->getToken()) {
            $this->activeUser = $this->requestStack->getCurrentRequest()->getUser();
        }
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        // inserts
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $auditTrailEntity = $this->auditTrailInsert($uow, $entity);
            if (null !== $auditTrailEntity) {
                $toBePersisted[spl_object_id($entity)] = $auditTrailEntity;
            }
        }

        // updates
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $auditTrailEntity = $this->auditTrailUpdate($uow, $entity);
            if (null !== $auditTrailEntity) {
                $toBePersisted[spl_object_id($entity)] = $auditTrailEntity;
            }
        }
        // champs OneToMany ou ManyToMany
        foreach ($uow->getScheduledCollectionUpdates() as $collection) {
            $entity = $collection->getOwner();
            $mapping = $collection->getMapping();
            $auditTrailEntity = $this->getAuditTrailEntity($entity);
            if (null !== $auditTrailEntity) {
                if (!in_array($mapping['fieldName'], $entity->getFieldsToBeIgnored(), true)) {
                    $before = $collection->getSnapShot();
                    if (array_key_exists(spl_object_id($entity), $toBePersisted)) {
                        $auditTrailEntity = $toBePersisted[spl_object_id($entity)];
                        $details = $auditTrailEntity->getDetails();
                        $details[$mapping['fieldName']] = [];
                        // added
                        foreach ($collection as $assocEntity) {
                            if (!in_array($assocEntity, $before)) {
                                $details[$mapping['fieldName']][] = $this->getChangeString($assocEntity);
                            }
                        }
                        if (!empty($details[$mapping['fieldName']])) {
                            $auditTrailEntity->setDetails($details);
                        }
                    } else {
                        $auditTrailEntity->setModifType(2);
                        $details = [$mapping['fieldName'] => ['added' => [], 'removed' => []]];
                        // added
                        foreach ($collection as $assocEntity) {
                            if (!in_array($assocEntity, $before)) {
                                $details[$mapping['fieldName']]['added'][] = $this->getChangeString($assocEntity);
                            }
                        }
                        // removed
                        foreach ($before as $assocEntity) {
                            if (!$collection->contains($assocEntity)) {
                                $details[$mapping['fieldName']]['removed'][] = $this->getChangeString($assocEntity);
                            }
                        }
                        // si modifs
                        if (!empty($details[$mapping['fieldName']]['added']) || !empty($details[$mapping['fieldName']]['removed'])) {
                            $auditTrailEntity->setDetails($details);
                            $toBePersisted[spl_object_id($entity)] = $auditTrailEntity;
                        }
                    }
                }
            }
        }

        // deletes
        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            // on ne supprime rien
            /*if ($auditTrailEntity = $this->auditTrailDelete($uow, $entity)) {
                $toBePersisted[spl_object_id($entity)] = $auditTrailEntity;
            }*/
        }
        foreach ($uow->getScheduledCollectionDeletions() as $collection) {
            // on ne supprime rien
            /*$entity = $collection->getOwner();
            $mapping = $collection->getMapping();
            $auditTrailEntity = $this->getAuditTrailEntity($entity);
            foreach ($collection as $entity) {
                if ($auditTrailEntity = $this->auditTrailDelete($uow, $entity)) {
                    $toBePersisted[spl_object_id($entity)] = $auditTrailEntity;
                }
            }*/
        }

        if (count($toBePersisted) > 0) {
            foreach ($toBePersisted as $auditTrailEntity) {
                $em->persist($auditTrailEntity);
            }
            $em->getEventManager()->removeEventListener([Events::onFlush], $this);
            $em->flush();
            $em->getEventManager()->addEventListener([Events::onFlush], $this);
        }
    }
}
