<?php

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as BaseAdminController;

use App\Entity\Contacto;
use App\Entity\Contenido;

class OngController extends BaseAdminController
{
    //accion se ejecuta cuando ingresa al formulario de ONG.
    public function newAction()
    {
        if (false === $this->isGranted('ROLE_SUPER_ADMIN')) {
            $user = $this->getUser();

            if ($user->getOng() or $user->getOngColaborator()) {
                $this->addFlash('info', 'Solo puede crear una ONG.');
                $response = $this->redirectToRoute('easyadmin', [
                    'entity' => 'Ong', 'action' => 'list'
                ]);
            } else {
                $response = parent::newAction();
            }
        } else {
            $response = parent::newAction();
        }

        return $response;
    }
    // Se ejecuta al crear new entity
    protected function createNewEntity()
    {
        $entity = parent::createNewEntity();

        // Busco contacto si existe y seteo los valores en la ONG al crear.
        $em = $this->getDoctrine()->getManager();
        $contacto = $em->getRepository(Contacto::class)
            ->findOneBy([
                'inscripcion' => true,
                'registro' => true,
                'cuil' => $this->getUser()->getCuitCuil()
            ]);
        if ($contacto) {
            $entity->setNombreOrganizacion($contacto->getOrganizacion());
            $entity->setCuit($contacto->getCuitOng());
            $entity->setApellido($contacto->getApellido());
            $entity->setNombre($contacto->getNombre());
            $entity->setCelular($contacto->getTelefono());
            $entity->setCorreo($contacto->getEmail());
            $entity->setObservaciones($contacto->getObservaciones());
        }

        return $entity;
    }
    //accion se ejecuta cuando se crea satisfactoriamente ONG.
    protected function persistEntity($entity)
    {
        parent::persistEntity($entity);
        // Envio correo al usuario que creo la ong
        if (false === $this->isGranted('ROLE_SUPER_ADMIN')) {
            $message = (new \Swift_Message('Nueva ONG'))
                ->setFrom('ecomgrupo11@gmail.com')
                ->setSubject('Nueva ONG: '.$entity->getNombreOrganizacion())
                ->setTo($this->getUser()->getEmail())
                ->setBody('
                    Estimado Usuario estaremos analizando su pedido a la brevedad.<br>
                    Se enviará por correo electrónico la activación de la cuenta.<br>
                    Muchas Gracias', 'text/html'
                );
        
            $this->get('mailer')->send($message);
        }
    }
    //accion se ejecuta cuando se actualiza satisfactoriamente la ONG.
    protected function updateEntity($entity)
    {
        $enviarCorreo = false;
        $em = $this->getDoctrine()->getManager();
        $uow = $em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeSet = $uow->getEntityChangeSet($entity);
        if ($changeSet) {
            if (array_key_exists("publicado", $changeSet)) {
                if ($changeSet['publicado'][1] == true) {
                    $enviarCorreo = true;
                }
            }
        }

        parent::updateEntity($entity);

        if ($enviarCorreo) {
            $setTos = $entity->getColaborators();
            if ($entity->getUser()) {
                $setTos[]=$entity->getUser();
            }
            // obtengo ahora el mail de soporte
            $soporte_mail = $this->getDoctrine()
                ->getRepository(Contenido::class)
                ->findOneByCodigo('soporte_mail');
            if ($soporte_mail) {
                $soporte_mail = $contenidoEntity->getContenido();
            } else {
                $soporte_mail = '';
            }

            // obtengo ahora el nro de telefono del soporte 
            $soporte_tel = $this->getDoctrine()
                ->getRepository(Contenido::class)
                ->findOneByCodigo('soporte_tel');
            if ($soporte_tel) {
                $soporte_tel = $contenidoEntity->getContenido();
            } else {
                $soporte_tel = '';
            }

            // Envio correo
            foreach ($setTos as $setTo) {
                $message = (new \Swift_Message('ONG activada'))
                    ->setFrom('ecomgrupo11@gmail.com')
                    ->setSubject('ONG: '.$entity->getNombreOrganizacion())
                    ->setTo($setTo->getEmail())
                    ->setBody(
                        $this->renderView(
                            // templates/emails/registration.html.twig
                            'mails/ong_activada.html.twig',
                            [
                                'soporte_tel' => $soporte_tel, 
                                'soporte_mail'=> $soporte_mail
                            ]
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
        }
    }
}
