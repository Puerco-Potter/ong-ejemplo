<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use App\Service\TGDService;
use App\Service\ApiClient;
use App\Entity\Colaborator;
use App\Entity\Contacto;
use App\Entity\Ong;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api_index")
     */
    public function index(Request $request, ApiClient $api)
    {
        $code = null;
        $data = null;
        
        if ($request->query->has('code')) {
            $code = $request->query->get('code');
            $api->setAccessToken($code);
            $data = $api->callApi('persona');
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/api/callback", name="api_callback")
     */
    public function callback(Request $request, TGDService $tgd)
    {
        $code = $request->query->get('code');

        $user = $tgd->getUserByCode($code);

        $em = $this->getDoctrine()->getManager();
        // Seteo como registrado en Contacto si existe
        $this->contactoRegistro($em, $user->getCuitCuil());
        // Obtener los colaboradores
        $colaborator = $this->getDoctrine()->getRepository(Colaborator::class)
            ->findOneByCuit($user->getCuitCuil());

        if (is_null($user->getOng())) {
            if (is_null($colaborator)) {
                return $this->redirectToRoute('easyadmin', [
                    'entity' => 'Ong', 'action' => 'new'
                ]);
            } else {
                // Si es una Jurisdiccion agrego el ROLE_JURISDICCION
                if ($colaborator->getOng()->getEsJurisdiccion()) {
                    $user->addRole('ROLE_JURISDICCION');
                    $em->persist($user);
                }
                // Agregamos al usuario a la ONG
                $colaborator->getOng()->addColaborator($user);

                $em->flush();
            }
        }

        return $this->redirectToRoute('easyadmin');
    }

    private function contactoRegistro($em, $cuil)
    {
        $contactos = $em->getRepository(Contacto::class)
            ->findBy(
                [ 'inscripcion' => true, 'cuil' => $cuil ],
                [ 'createdAt' => 'DESC' ]
            );
        if (!empty($contactos)) {
            $contactos[0]->setRegistro(true);

            $em->persist($contactos[0]);
            $em->flush();
        }
    }
}
