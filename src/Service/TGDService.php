<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Service\OAuthClient;
use App\Service\ApiClient;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Class TGDService
 */
class TGDService {

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var OAuthClient */
    private $oauthClient;

    /** @var ApiClient */
    private $apiClient;
    
    /** @var EntityManager */
    private $em;

    public function __construct(TokenStorageInterface $tokenStorage, ParameterBagInterface $params,
                                ApiClient $apiClient, EntityManager $em
    ) {
        $oauthBaseUri = $params->get('oauth_base_uri');
        $oauthClientId = $params->get('oauth_client_id');
        $oauthClientSecret = $params->get('oauth_client_secret');
        $this->redirectUri = $this->setRedirectUri($params->get('oauth_redirect_uri'));

        //Se van a utilizar dos clientes http, uno que maneje el protocolo OAuth, y el otro para llamar a las Apis.
        $this->oauthClient = new OAuthClient($oauthClientId, $oauthClientSecret, $oauthBaseUri);
        $this->apiClient = $apiClient;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    // Unimos el HOST con el fragmento pasado
    private function setRedirectUri(string $value) : string
    {
        return (isset($_SERVER["HTTPS"])?'https://':'http://') . $_SERVER["HTTP_HOST"] . $value;
    }

    /**
     * TGD lanza esta aplicacion con con code.
     * Es decir : http://connecttgd/callback?code=xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
     */
    public function getUserByCode($code, $gestor = null)
    {
        //$this->redirectUri = 'http://ong-prueba.chaco.gov.ar/api/callback';// DESCOMENTAR CUANDO ESTAMOS EN LOCAL

        $tokenInfo = $this->oauthClient->getTokenByCode($code, $this->redirectUri);

        $this->apiClient->setAccessToken($tokenInfo['access_token']);

        $personaInfo = $this->apiClient->callApi('persona');

        $user = $this->saveOrUpdateUser($personaInfo, $tokenInfo);

        return $user;
    }

    /**
     * Crea o actualiza el usuario con los datos de la persona y los del token.
     *
     * Se deben seleccionar los datos que vienen de la petición de la api de persona.
     * Lo importante es guardar siempre el id de la persona en tgd.
     */
    protected function saveOrUpdateUser($personaInfo, $tokenInfo)
    {
        // Busco si ya lo tengo al usuario por el id de persona en tgd
        $user = $this->em->getRepository(User::class)
            ->findOneByTgdPersonaId($personaInfo['id']);

        if ($user) {
            // Actualizo los datos del usuario con los datos que viene de la api
            $user->setEmail($personaInfo['emails'][0]['email']);
            $user->setCuitCuil($personaInfo['cuitCuil']);
            $user->setAccessToken($tokenInfo['access_token']);
            $user->setRefreshToken($tokenInfo['refresh_token']);
            $user->setExpiresInToken($tokenInfo['expires_in']);
            if (!$user->hasRole('ROLE_USER')) {
                $user->addRole('ROLE_USER');
            }
        } else {
            // Si el usuario no existe lo creamos
            $user = new User();
            $user->setEmail($personaInfo['emails'][0]['email']);
            $user->setPassword($personaInfo['emails'][0]['email']);
            $user->setTgdPersonaId($personaInfo['id']);
            $user->setCuitCuil($personaInfo['cuitCuil']);
            $user->setAccessToken($tokenInfo['access_token']);
            $user->setRefreshToken($tokenInfo['refresh_token']);
            $user->setExpiresInToken($tokenInfo['expires_in']);
            $user->addRole('ROLE_USER');
            $this->em->persist($user);
        }

        $this->em->flush();

        // Login manual
        $token = $this->createToken('main', $user);
        $this->tokenStorage->setToken($token);

        return $user;
    }

    /**
     * @return UsernamePasswordToken
     */
    protected function createToken($firewall, $user)
    {
        return new UsernamePasswordToken($user, null, $firewall, $user->getRoles());
    }
}