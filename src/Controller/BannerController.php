<?php

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as BaseAdminController;
//filtro tematica
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tematica;
use App\Entity\Contenido;
use App\Entity\Noticia;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\PersistentCollection;

class BannerController extends BaseAdminController
{
    public function createEntityFormBuilder($entity, $view)
    {
        
        $formBuilder = parent::createEntityFormBuilder($entity, $view);
        
        $formBuilder->add('noticia', EntityType::class, array(
            'class' => Noticia::class,
            'placeholder' => 'Ninguno',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('n')
                    ->where('n.publicado=1')
                    ->orderBy('n.id', 'ASC');
            },
        ));

        return $formBuilder;
    }
}