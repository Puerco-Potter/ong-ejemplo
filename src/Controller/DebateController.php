<?php

namespace App\Controller;

use AlterPHP\EasyAdminExtensionBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use App\Entity\ComentarioDebate;
use App\Entity\Debate;
use App\Form\ComentarioDebateType;

use Symfony\Component\HttpFoundation\Request;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\Common\Collections\ArrayCollection;

//filtro tematica
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Tematica;
use App\Entity\Contenido;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;

class DebateController extends BaseAdminController
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

    public function editAction()
    {
        $user = $this->getUser();

        if ((false === $this->isGranted('ROLE_SUPER_ADMIN'))) {
            $easyadmin = $this->request->attributes->get('easyadmin');
            $entity = $easyadmin['item'];
            if ($entity->getPublicado()) {
                $this->addFlash('info', 'No se puede editar una el debate publicado.');
                $response = $this->redirectToRoute('easyadmin', [
                    'entity' => 'Debate', 'action' => 'list'
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
                $this->addFlash('info', 'No se puede eliminar el debate publicado.');
                $response = $this->redirectToRoute('easyadmin', [
                    'entity' => 'Debate', 'action' => 'list'
                ]);
            } else {
                $response = parent::deleteAction();
            }
        } else {
            $response = parent::deleteAction();
        }

        return $response;
    }


    

   /**
     * @Route("/mostrar_debate", name="mostrar_debate")
     */
    public function mostrarDebate(Request $request)
    {   
        
        $id = $request->query->get('id');
        
        //tema principal
        $debate=$this->getDoctrine()
        ->getRepository(Debate::class)
        ->findOneBy(array('id'=>$id));     

        //paginado de todos los comentarios del debate
        $queryBuilder =$this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('c')
            ->from('App\Entity\ComentarioDebate', 'c')
            ->join('c.debate', 'd',  'WITH', 'd.id = :debate')
            ->orderBy('c.fechaCreacion','ASC')
            ->setParameter('debate',$debate->getId());

        $adapter = new DoctrineORMAdapter($queryBuilder);
        $paginado_comentarios = new Pagerfanta($adapter);
        // el bundle ocupa como variable request el page, indicando el numero de pagina actual

        if (isset($_REQUEST['page'])) {
            $paginado_comentarios =$paginado_comentarios->setCurrentPage($_REQUEST['page']);
        } ;


        //formulario para el nuevo comentario
        $comentario = new ComentarioDebate();
        $form_comentario = $this->createForm(ComentarioDebateType::class, $comentario);


        $form_comentario->handleRequest($request);

        if ($form_comentario->isSubmitted() && $form_comentario->isValid()) {
            $comentario_ingresado = $form_comentario->getData();
            //creo el comentario
            $fecha = new \DateTime();
            $comentario_ingresado->setFechaCreacion($fecha);
            $comentario_ingresado->setDebate($debate);
            $user=$this->container->get('security.token_storage')->getToken()->getUser();

            if (is_callable([$user,'getOng'])) {
                $comentario_ingresado->setUser($user);
                $comentario_ingresado->setOng($user->getOng());
            }
           

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($comentario_ingresado);
            $entityManager->flush();

            //obtengo los usuarios (ong y colaboradores) para notificarles de los comentarios
            $usuarios=$this->getDoctrine()
            ->getRepository(User::class)
            ->findBy(['enabled'=>1]);

            //obtengo el mail del administrador y los datos de usuario de soporte
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

            //ahora mando el mail a todos los usuarios (ong y colaboradores)
            foreach ($usuarios as $usuario) {
                $message = (new \Swift_Message('Nueva entrada'))
                ->setFrom('ecomgrupo11@gmail.com')
                ->setSubject('Nueva entrada en el debate: '.$debate->getTitulo())
                ->setTo($usuario->getEmail())
                ->setBody(
                    $this->renderView(
                       'mails/comentario_debate.html.twig',
                       array(
                           'soporte_tel' => $soporte_tel, 
                           'soporte_mail'=> $soporte_mail,
                           'debate'=>$debate,
                           'comentario'=>$comentario_ingresado
                        )
                    ),
                    'text/html'
                );
                
                $this->get('mailer')->send($message);
            } 
        
        //ahora mando el mail al administrador
        foreach ($administradores as $admin) {
            if ($admin->getTexto()) {
                $message = (new \Swift_Message('Nueva entrada'))
                ->setFrom('ecomgrupo11@gmail.com')
                ->setSubject('Nueva entrada en el debate: '.$debate->getTitulo())
                ->setTo($admin->getTexto())
                ->setBody(
                    $this->renderView(
                       'mails/comentario_debate.html.twig',
                       array(
                           'soporte_tel' => $soporte_tel, 
                           'soporte_mail'=> $soporte_mail,
                           'debate'=>$debate,
                           'comentario'=>$comentario_ingresado
                        )
                    ),
                    'text/html'
                );
                
                $this->get('mailer')->send($message);
            }
            
            }
        }

        return $this->render('backend/debate/debate.html.twig',array(
            'debate'=>$debate,
            'paginado_comentarios' =>$paginado_comentarios,
            'form_comentario' => $form_comentario->createView(),
            ));
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

    //cuando se crea una agenda tengo que avisar al superadmin si es que lo publico un usuario
    protected function persistEntity($entity)
    {   
        parent::persistEntity($entity);
        //obtengo los usuarios (ong y colaboradores) para notificarles de los comentarios
        $usuarios=$this->getDoctrine()
        ->getRepository(User::class)
        ->findBy(['enabled'=>1]);
    

        //obtengo el mail del administrador y los datos de usuario de soporte
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

        //ahora mando el mail a todos los usuarios (ong y colaboradores)
        foreach ($usuarios as $usuario) {
            $message = (new \Swift_Message('Nueva entrada'))
            ->setFrom('ecomgrupo11@gmail.com')
            ->setSubject('Nueva debate creado: '.$entity->getTitulo())
            ->setTo($usuario->getEmail())
            ->setBody(
                $this->renderView(
                   'mails/nuevo_debate.html.twig',
                   array(
                       'soporte_tel' => $soporte_tel, 
                       'soporte_mail'=> $soporte_mail,
                       'debate'=>$entity,
                    )
                ),
                'text/html'
            );
            
            $this->get('mailer')->send($message);
        } 
    
    //ahora mando el mail al administrador
    foreach ($administradores as $admin) {
        if ($admin->getTexto()) {
            $message = (new \Swift_Message('Nueva entrada'))
            ->setFrom('ecomgrupo11@gmail.com')
            ->setSubject('Nuevo debate creado: '.$entity->getTitulo())
            ->setTo($admin->getTexto())
            ->setBody(
                $this->renderView(
                   'mails/nuevo_debate.html.twig',
                   array(
                       'soporte_tel' => $soporte_tel, 
                       'soporte_mail'=> $soporte_mail,
                       'debate'=>$entity,
                    )
                ),
                'text/html'
            );
            
            $this->get('mailer')->send($message);
        }
        
    }

        
    }

}
