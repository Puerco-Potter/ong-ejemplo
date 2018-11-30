<?php

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as BaseAdminController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tematica;
use App\Entity\Contenido;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class AgendaController extends BaseAdminController
{
    //accion se ejecuta cuando ingresa al formulario de ONG.
    public function newAction()
    {
        if (false === $this->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->getUser();
            $ong = $user->getOng();
            if ($ong) {
                if (false === $ong->getPublicado()) {
                    $this->addFlash('warning', 'Aguarde que su ONG sea habilitada.');
                    $response = $this->redirectToRoute('easyadmin', [
                        'entity' => 'Ong', 'action' => 'list'
                    ]);
                } else {
                    $response = parent::newAction();
                }
            } else {
                $ongColaborator = $user->getOngColaborator();
                if ($ongColaborator) {
                    if (false === $ongColaborator->getPublicado()) {
                        $this->addFlash('warning', 'Aguarde que su ONG sea habilitada.');
                        $response = $this->redirectToRoute('easyadmin', [
                            'entity' => 'Ong', 'action' => 'list'
                        ]);
                    } else {
                        $response = parent::newAction();
                    }
                } else {
                    $this->addFlash('warning', 'Complete los datos de su ONG, o solicite ser colaborador de una ya existente.');
                    $response = $this->redirectToRoute('easyadmin', [
                        'entity' => 'Ong', 'action' => 'new'
                    ]);
                }
            }
        } else {
            $response = parent::newAction();
        }

        return $response;
    }


    //cuando se crea una agenda tengo que avisar al superadmin si es que lo publico un usuario
    protected function persistEntity($entity)
    {   
        parent::persistEntity($entity);
        
        $administradores = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findBy(array('codigo'=>'correo_superadmin'));
            
        $soporte_mail = $this->getDoctrine()
            ->getRepository(Contenido::class)
            ->findOneByCodigo('soporte_mail');

        if ($soporte_mail) {
            $soporte_mail = $soporte_mail->getTexto();
        } else {
            $soporte_mail = '';
        }

        // obtengo ahora el nro de telefono del soporte 
        $soporte_tel = $this->getDoctrine()
        ->getRepository(Contenido::class)
        ->findOneByCodigo('soporte_tel');

        if ($soporte_tel) {
            $soporte_tel = $soporte_tel->getTexto();
        } else {
            $soporte_tel = '';
        }
    
        // Envio correo al administrador
        if (false === $this->isGranted('ROLE_SUPER_ADMIN')) {
            foreach ($administradores as $administrador) {
                $message = (new \Swift_Message('Nueva ONG'))
                ->setFrom('ecomgrupo11@gmail.com')
                ->setSubject('Nuevo evento creado: '.$entity->getTitulo())
                ->setTo($administrador->getTexto())
                ->setBody(
                    $this->renderView(
                    'mails/aviso_entrada.html.twig',
                    array(
                    'soporte_tel' => $soporte_tel, 
                    'soporte_mail'=> $soporte_mail,
                    'entrada'=>$entity,
                    'tipo'=>3
                    )), 'text/html'
                );
        
                $this->get('mailer')->send($message);
            }
        }
    }

    //cuando se publica un nuevo evento de la agenda se notifica a todos los usuarios registrados
    protected function updateEntity($entity)
    {
         $enviarCorreo_publicado = false;
         $em = $this->getDoctrine()->getManager();
         $uow = $em->getUnitOfWork();
         $uow->computeChangeSets();
         $changeSet = $uow->getEntityChangeSet($entity);
         if ($changeSet) {
             if (array_key_exists("publicado", $changeSet)) {
                 if ($changeSet['publicado'][1] == true) {
                     $enviarCorreo_publicado = true;
                 }
             }
         }
 
         
         

         //verificio si se modificaron los comentarios
         $modificacion_comentarios=$entity->getComentarios()->isDirty(); 
         $nuevos_comentarios=[];
         if($modificacion_comentarios){
            $nuevos_comentarios=$entity->getComentarios()->getInsertDiff();
         }

         parent::updateEntity($entity);
         
         $soporte_mail = $this->getDoctrine()
         ->getRepository(Contenido::class)
         ->findOneByCodigo('soporte_mail');

         if ($soporte_mail) {
             $soporte_mail = $soporte_mail->getTexto();
         } else {
             $soporte_mail = '';
         }

     // obtengo ahora el nro de telefono del soporte 
         $soporte_tel = $this->getDoctrine()
         ->getRepository(Contenido::class)
         ->findOneByCodigo('soporte_tel');
         if ($soporte_tel) {
             $soporte_tel = $soporte_tel->getTexto();
         } else {
             $soporte_tel = '';
         }

         if ($modificacion_comentarios) {
            //obtengo los nuevos comentarios
          
            //obtengo los usuarios y los colaboradores para notificarles de los comentarios
            $usuario=$entity->getUser();
            $colaboradores=[];
            if ($entity->getOng()) {
                $colaboradores=$entity->getOng()->getColaborators();
            }
      
            $usuarios=$colaboradores;
            $usuarios[]=$usuario;

            foreach ($usuarios as $user) {
                $message = (new \Swift_Message('Nuevo comentario en evento'))
                     ->setFrom('ecomgrupo11@gmail.com')
                     ->setSubject('Nuevo comentario en evento : '.$entity->getTitulo())
                     ->setTo($user->getEmail())
                     ->setBody(
                         $this->renderView(
                            'mails/comentario_entrada.html.twig',
                            array(
                                'soporte_tel' => $soporte_tel, 
                                'soporte_mail'=> $soporte_mail,
                                'entrada'=>$entity,
                                'tipo'=>3,
                                'nuevos_comentarios'=>$nuevos_comentarios
                             )
                         ),
                         'text/html'
                     );
                    
                     $this->get('mailer')->send($message);
            }
         }
         if ($enviarCorreo_publicado) {
             //obtengo todos los usuarios (ong y colaboradores)
             $usuarios=$this->getDoctrine()
             ->getRepository(User::class)
             ->findBy(['enabled'=>1]);
    
             // Envio correo
             foreach ($usuarios as $usuario) {
                 
                 $message = (new \Swift_Message('Nuevo evento'))
                     ->setFrom('ecomgrupo11@gmail.com')
                     ->setSubject('Nuevo evento: '.$entity->getTitulo())
                     ->setTo($usuario->getEmail())
                     ->setBody(
                         $this->renderView(
                            'mails/nueva_entrada.html.twig',
                            array(
                                'soporte_tel' => $soporte_tel, 
                                'soporte_mail'=> $soporte_mail,
                                'tipo'=>3,
                                'entrada'=>$entity
                             )
                         ),
                         'text/html'
                     );
                    
                     $this->get('mailer')->send($message);
            }
        }
    }
     
    public function editAction()
    {
        $user = $this->getUser();

        if ((false === $this->isGranted('ROLE_SUPER_ADMIN'))) {
            $easyadmin = $this->request->attributes->get('easyadmin');
            $entity = $easyadmin['item'];
            if ($entity->getPublicado()) {
                $this->addFlash('info', 'No se puede editar una agenda publicada.');
                $response = $this->redirectToRoute('easyadmin', [
                    'entity' => 'Agenda', 'action' => 'list'
                ]);
            } else {
                $response = parent::editAction();
            }
        } else {
            $response = parent::editAction();
        }

        return $response;
    }

    
    

    public function deleteAction()
    {
        $user = $this->getUser();

        if ((false === $this->isGranted('ROLE_SUPER_ADMIN'))) {
            $easyadmin = $this->request->attributes->get('easyadmin');
            $entity = $easyadmin['item'];
            if ($entity->getPublicado()) {
                $this->addFlash('info', 'No se puede eliminar una agenda publicada.');
                $response = $this->redirectToRoute('easyadmin', [
                    'entity' => 'Agenda', 'action' => 'list'
                ]);
            } else {
                $response = parent::deleteAction();
            }
        } else {
            $response = parent::deleteAction();
        }

        return $response;
    }

    public function createEntityFormBuilder($entity, $view)
    {
        //consigo las tematicas de ese usuario
        if (false === $this->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->getUser();
            $ong = $user->getOng();
            if ($ong){
                $tematicas = $ong->getTematicas();
            } else{
                $ongColaborator = $user->getOngColaborator();
                $tematicas = $ongColaborator->getTematicas();
            }
        }else{
            //o todas las tematicas
            $tematicas = $this->getDoctrine()
            ->getRepository(Tematica::Class)->findAll();
        }
        //armo el string para el WHERE IN
        $tematicasIN = "( ";
        foreach($tematicas as $tematica){
            $tematicasIN = $tematicasIN . $tematica->getId() . ", ";
        }
        //quito la ultima coma
        if (count($tematicas) != 0){
            $tematicasIN = substr($tematicasIN, 0, -2);
        } else{
            $tematicasIN = $tematicasIN . "0 ";
        }
        $tematicasIN = $tematicasIN . ")";
        
        $formBuilder = parent::createEntityFormBuilder($entity, $view);
        
        $formBuilder->add('tematicas', EntityType::class, array(
            'class' => Tematica::class,
            'multiple'  => true,
            'expanded'  => true,
            'query_builder' => function (EntityRepository $er) use ($tematicasIN) {
                return $er->createQueryBuilder('t')
                    ->where('t.id IN ' . $tematicasIN )
                    ->orderBy('t.nombre', 'ASC');
            },
        ));
        return $formBuilder;
    }
}
