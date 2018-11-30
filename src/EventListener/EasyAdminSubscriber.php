<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use App\Entity\Ong;
use App\Entity\Debate;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

use Doctrine\Common\Collections\Criteria;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $authChecker;
    private $tokenStorage;
    private $redirectController;

    public function __construct(AuthorizationCheckerInterface $authChecker, TokenStorageInterface $tokenStorage)
    {
        $this->authChecker = $authChecker;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'easy_admin.pre_persist' => array('setEntityPrePersist'),
            'easy_admin.post_list_query_builder' => array('postListQueryBuilder'),
            'easy_admin.post_search_query_builder' => array('postListQueryBuilder'),
        );
    }

    //Action by create entity
    public function setEntityPrePersist(GenericEvent $event)
    {
        $entity = $event->getSubject();

        if (method_exists($entity, 'setFechaCreacion')) {
            $entity->setFechaCreacion(new \DateTime());
        }
        if (false === $this->authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            if (method_exists($entity, 'setUser')) {
                $entity->setUser($this->tokenStorage->getToken()->getUser());
            }
            if (method_exists($entity, 'setOng')) {
                $user = $this->tokenStorage->getToken()->getUser();
                
                if ($user->getOng()) {
                    $ong = $user->getOng();
                } else {
                    $ong = $user->getOngColaborator();
                }

                $entity->setOng($ong);
            }
        }

        $event['entity'] = $entity;
    }
    //Filter by user and role
    public function postListQueryBuilder(GenericEvent $event)
    {
        $entity = $event->getArgument('entity');
        // $sortField = $event->getArgument('sort_field');
        // $sortDirection = $event->getArgument('sort_direction');
        if (false === $this->authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            //Si no es Superadmin entra
            $queryBuilder = $event->getArgument('query_builder');
            $expr = Criteria::expr();
            $criteria = Criteria::create();

            if ($entity['class'] != Ong::class) {
                //Si no es Ong entra
                if ($entity['class'] != Debate::class) {
                    //Si no es Debate entra
                    if (array_key_exists('ong', $entity['properties'])) {
                        $user = $this->tokenStorage->getToken()->getUser();
                        if ($user->getOng()) {
                            $ong = $user->getOng();
                        } else {
                            $ong = $user->getOngColaborator();
                        }
                        $criteria
                            ->andWhere($expr->eq('ong', $ong))
                            ->andWhere($expr->neq('ong', null));
                        $queryBuilder->addCriteria($criteria);
                    }
                }
            } else {
                //Aqui es Ong
                if (array_key_exists('user', $entity['properties'])) {
                    $queryBuilder = $event->getArgument('query_builder');
                    $queryBuilder->leftJoin('entity.colaborators', 'c');

                    $criteria->orWhere($expr->eq('user', $this->tokenStorage->getToken()->getUser()));
                    $criteria->orWhere($expr->eq('c', $this->tokenStorage->getToken()->getUser()));

                    $queryBuilder->addCriteria($criteria);
                }
            }
        }

        return;
    }
}