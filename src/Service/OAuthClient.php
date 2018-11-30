<?php

namespace App\Service;

use GuzzleHttp\Client;

class OAuthClient
{
    /** @var string */
    private $clientId;

    /** @var string */
    private $clientSecret;

    /** @var Client */
    private $httpClient;

    public function __construct($clientId, $clientSecret, $baseUri)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->httpClient = new Client(array('base_uri' => $baseUri));
    }

    /**
     * Obtención de token a traves de code
     *
     * Ej para el servidor de pruebas (http://stage.ventanillaunica.chaco.gov.ar)
     * (En producción los dos servidores deben ser https)
     *
     * Peticion a TGD:
     * http://stage.ventanillaunica.chaco.gov.ar/oauth/v2/token?client_id=xxxxxxxxxxxxxxxxxxx&client_secret=yyyyyyyyyyyyyyyyy&grant_type=authorization_code=redirect_url=zzzzzzzzzzzzzzzzzzzz&code=kkkkkkkkkkkkkkkkkkk
     *
     * Ej de respuesta:
     * {
     *     "access_token":"YTI0YTM1ZTJhNGQwNGIyZTEyZGM5NGVkNTg0YzJiNGQ2NTM4MDhjNDgzNDQ2OTdiMzNjNmJmY2RiNWU2MmY3Ng",
     *     "expires_in":3600,
     *     "token_type":"bearer",
     *     "scope":null,
     *     "refresh_token":"ZWM3MGU5YTYxYWFjYmFhYmNkNGMxM2U3Y2FmNDJlYjE2MzUwMzFjZWExY2IzZmQyMzUyNDRkMTg5MTJkNTAxMA"
     * }
     */
    public function getTokenByCode($code, $redirectUri)
    {
        $query = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'authorization_code',
            'redirect_uri'  => $redirectUri,
            'code'          => $code,
        );

        return $this->call($query, 'token');
    }

    public function getTokenByRefresh($refreshToken)
    {
        $query = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        );

        return $this->call($query, 'token');
    }

    public function getClientCredentialsToken()
    {
        $query = array(
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type'    => 'client_credentials',
        );

        return $this->call($query, 'token');
    }

    private function call($query, $uri, $method = 'GET')
    {
        $options = array('query' => $query);

        if ($method == 'POST') {
            $options = array('form_params' => $query);
        }

        $response = $this->httpClient->request($method, $uri, $options);
        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }
}