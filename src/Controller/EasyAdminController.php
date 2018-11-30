<?php

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as BaseAdminController;

class EasyAdminController extends BaseAdminController
{
    public function createNewUserEntity()
    {   
        return $this->get('fos_user.user_manager')->createUser();
    }
    public function persistUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::persistEntity($user);
    }
    public function updateUserEntity($user)
    {
        $this->get('fos_user.user_manager')->updateUser($user, false);
        parent::updateEntity($user);
    }
    //accion que se ejecuta cuando actualizas una entidad
    public function updateEntity($entity)
    {
        if (method_exists($entity, 'setFechaModificacion')) {
            $entity->setFechaModificacion(new \DateTime());
        }

        parent::updateEntity($entity);
    }
}
