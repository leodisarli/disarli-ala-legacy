<?php

namespace App\Services;

use App\Exceptions\Custom\ExternalApiException;
use App\Exceptions\Custom\InvalidCredentialsException;
use App\Helpers\DateTimeHelper;
use GuzzleHttp\Client;

class ExternalApiService
{
    private $guzzleClient;
    private $apiConf;

    /**
     * constructor
     * @param Client $guzzleClient
     */
    public function __construct(
        Client $guzzleClient
    ) {
        $this->guzzleClient = $guzzleClient;
        $this->apiConf = config('externalapi');
    }

    /**
     * get a jwt token from api
     * @param int $cliCod
     * @param string $email
     * @param string $apiKey
     * @throws ExternalApiException
     * @throws InvalidCredentialsException
     * @return string $jwtToken
     */
    public function getJwtViaCredentials(
        int $cliCod,
        string $email,
        string $apiKey
    ) : ? string {
        $postData = [
            'publickey' => $cliCod,
            'apikey' => $apiKey,
            'email' => $email,
        ];
        $response = $this->guzzleClient->request(
            'POST',
            $this->apiConf['url'].'credential/generate_token',
            [
                'form_params' => $postData,
                'http_errors' => false,
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 401) {
            throw new InvalidCredentialsException();
        }
        if ($statusCode != 200) {
            throw new ExternalApiException();
        }

        $dataJson = (string) $response->getBody();
        $dataArray = json_decode($dataJson, true);
        
        $jwtToken = $dataArray['data']['token'] ?? null;
        return $jwtToken;
    }
   
    /**
     * get a jwt token from api
     * @param int $cliCod
     * @param string $email
     * @param string $apiKey
     * @throws ExternalApiException
     * @throws InvalidCredentialsException
     * @return string $jwtToken
     */
    public function getJwtPartnerViaCredentials() : ? string {
        $postData = [
            'partner_token' => $this->apiConf['token'],
            'partner_secret' => $this->apiConf['secret'],
        ];
        $response = $this->guzzleClient->request(
            'POST',
            $this->apiConf['url'].'credential/generate_partner_token',
            [
                'form_params' => $postData,
                'http_errors' => false,
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 401) {
            throw new InvalidCredentialsException();
        }
        if ($statusCode != 200) {
            throw new ExternalApiException();
        }

        $dataJson = (string) $response->getBody();
        $dataArray = json_decode($dataJson, true);
        
        $jwtToken = $dataArray['data']['token'] ?? null;
        return $jwtToken;
    }

    /**
     * get a jwt token from api via email and pass
     * @param string $email
     * @param string $pass
     * @throws ExternalApiException
     * @throws InvalidCredentialsException
     * @return string $jwtToken
     */
    public function getJwtViaLogin(
        string $email,
        string $pass
    ) : ? string {
        $postData = [
            'email' => $email,
            'password' => $pass,
            'partner_token' => $this->apiConf['token'],
            'partner_secret' => $this->apiConf['secret'],
        ];
        $response = $this->guzzleClient->request(
            'POST',
            $this->apiConf['url'].'credential/generate_token_via_login',
            [
                'form_params' => $postData,
                'http_errors' => false,
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 401) {
            throw new InvalidCredentialsException();
        }
        if ($statusCode != 200) {
            throw new ExternalApiException();
        }

        $dataJson = (string) $response->getBody();
        $dataArray = json_decode($dataJson, true);
        
        $jwtToken = $dataArray['data']['token'] ?? null;
        return $jwtToken;
    }

    /**
     * check if jwt exists and is valid
     * @param array $jwtData
     * @return bool
     */
    public function validJwt(
        array $jwtData
    ) : bool {
        $jwt = $jwtData['jwt'] ?? null;
        $validUntil = $jwtData['valid_until'] ?? null;

        if (empty($jwt) || empty($validUntil)) {
            return false;
        }

        $dateTime = new DateTimeHelper();
        $onValidity = $dateTime->onValidity($validUntil);
        if (!$onValidity) {
            return false;
        }
        return true;
    }

    /**
     * get to an api endpoint
     * @param int $cliCod
     * @param string $email
     * @param string $apiKey
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @throws ExternalApiException
     * @throws InvalidCredentialsException
     * @return array $response
     */
    public function requestApi(
        int $cliCod,
        string $email,
        string $apiKey,
        string $method,
        string $endpoint,
        array $jwtData = [],
        array $params = []
    ) : array {

        $jwt = $jwtData['jwt'] ?? null;
        $validJwt = $this->validJwt($jwtData);
        if (!$validJwt) {
            $jwt = $this->getJwtViaCredentials(
                $cliCod,
                $email,
                $apiKey
            );
        }

        $response = $this->guzzleClient->request(
            $method,
            $this->apiConf['url'].$endpoint,
            [
                'headers' => [
                    'token' => $jwt,
                ],
                'form_params' => $params,
                'http_errors' => false,
            ]
        );
        $statusCode = $response->getStatusCode();

        if ($statusCode == 401) {
            throw new InvalidCredentialsException();
        }
        if ($statusCode != 200) {
            throw new ExternalApiException();
        }

        $responseJson = (string) $response->getBody();
        $responseArray = json_decode($responseJson, true);
        
        $response = [
            'data' =>  $responseArray['data'] ?? [],
            'jwt' => [
                'token' => $responseArray['profile']['token'] ?? null,
                'valid_until' => $responseArray['profile']['token_valid_until'] ?? null,
            ],
            'paginator' => $responseArray['paginator'] ?? null,
        ];

        return $response;
    }

    /**
     * get to an api partner endpoint
     * @param int $cliCod
     * @param string $email
     * @param string $apiKey
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @throws ExternalApiException
     * @throws InvalidCredentialsException
     * @return array $response
     */
    public function requestApiPartner(
        string $method,
        string $endpoint,
        array $jwtData = [],
        array $params = []
    ) : array {

        $jwt = $jwtData['jwt'] ?? null;
        $validJwt = $this->validJwt($jwtData);
        if (!$validJwt) {
            $jwt = $this->getJwtPartnerViaCredentials();
        }

        $response = $this->guzzleClient->request(
            $method,
            $this->apiConf['url'].'partner/'.$endpoint,
            [
                'headers' => [
                    'token' => $jwt,
                ],
                'form_params' => $params,
                'http_errors' => false,
            ]
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode == 401) {
            throw new InvalidCredentialsException();
        }
        if ($statusCode != 200) {
            throw new ExternalApiException();
        }

        $responseJson = (string) $response->getBody();
        $responseArray = json_decode($responseJson, true);
        
        $response = [
            'data' =>  $responseArray['data'] ?? [],
            'jwt' => [
                'token' => $responseArray['profile']['token'] ?? null,
                'valid_until' => $responseArray['profile']['token_valid_until'] ?? null,
            ],
            'paginator' => $responseArray['paginator'] ?? null,
        ];

        return $response;
    }

    /**
     * get to an api endpoint
     * @param string $email
     * @param string $pass
     * @throws ExternalApiException
     * @throws InvalidCredentialsException
     * @return array $response
     */
    public function loginApi(
        string $email,
        string $pass
    ) : array {

        $jwt = $this->getJwtViaLogin(
            $email,
            $pass
        );

        $response = $this->guzzleClient->request(
            'GET',
            $this->apiConf['url'].'user/get_me',
            [
                'headers' => [
                    'token' => $jwt,
                ],
                'http_errors' => false,
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode == 401) {
            throw new InvalidCredentialsException();
        }
        if ($statusCode != 200) {
            throw new ExternalApiException();
        }

        $responseJson = (string) $response->getBody();
        $responseArray = json_decode($responseJson, true);

        $cliCod = $responseArray['data'][0]['user_id'] ?? null;
        $apiKey = $responseArray['data'][0]['api_key'] ?? null;
        if (empty($cliCod) || empty($apiKey)) {
            throw new ExternalApiException();
        }

        $loginData = [
            'cli_cod' => $cliCod,
            'api_key' => $apiKey,
            'name' => $responseArray['data'][0]['fantasy'] ?? null,
            'document' => $responseArray['data'][0]['taxid'] ?? null,
            'email' => $responseArray['data'][0]['email'] ?? null,
            'photo' => $responseArray['data'][0]['photo'] ?? null,
            'token' => $responseArray['profile']['token'] ?? null,
            'valid_until' => $responseArray['profile']['token_valid_until'] ?? null,
        ];
        return $loginData;
    }
}
