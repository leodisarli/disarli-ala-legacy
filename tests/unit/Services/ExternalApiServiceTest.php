<?php

namespace App\Services;

use \Mockery;
use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ExternalApiServiceTest extends TestCase
{
    /**
     * @covers \App\Services\ExternalApiService::__construct
     */
    public function testExternalApiServiceCanBeInstantiated()
    {
        $guzzleSpy = Mockery::spy(Client::class);
        $service = new ExternalApiService($guzzleSpy);
        $this->assertInstanceOf(ExternalApiService::class, $service);
    }

    /**
     * @covers \App\Services\ExternalApiService::getJwtViaCredentials
     */
    public function testGetJwtViaCredentials()
    {
        $cliCod = 123;
        $email = 'test@email.com';
        $apiKey = '1a2b3c4d5e';

        $json = '{"data":{"token":"abc"}}';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtViaCredentials(
            $cliCod,
            $email,
            $apiKey
        );
        $this->assertInternalType('string', $response);
        $this->assertEquals('abc', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::getJwtViaCredentials
     * @expectedException App\Exceptions\Custom\InvalidCredentialsException
     */
    public function testGetJwtViaCredentialsInvalid()
    {
        $cliCod = 456;
        $email = 'test@email.com';
        $apiKey = '1a2b3c4d5e';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(401)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtViaCredentials(
            $cliCod,
            $email,
            $apiKey
        );
    }

        /**
     * @covers \App\Services\ExternalApiService::getJwtViaCredentials
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testGetJwtViaCredentialsError()
    {
        $cliCod = 123;
        $email = 'test@email.com';
        $apiKey = '1a2b3c4d5e';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(500)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtViaCredentials(
            $cliCod,
            $email,
            $apiKey
        );
    }

    /**
     * @covers \App\Services\ExternalApiService::getJwtViaLogin
     */
    public function testGetJwtViaLogin()
    {
        $email = 'test@email.com';
        $pass = '123456';

        $json = '{"data":{"token":"abc"}}';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtViaLogin(
            $email,
            $pass
        );
        $this->assertInternalType('string', $response);
        $this->assertEquals('abc', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::getJwtViaLogin
     * @expectedException App\Exceptions\Custom\InvalidCredentialsException
     */
    public function testGetJwtViaLoginInvalid()
    {
        $email = 'test@email.com';
        $pass = '456123';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(401)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtViaLogin(
            $email,
            $pass
        );
    }

    /**
     * @covers \App\Services\ExternalApiService::getJwtViaLogin
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testGetJwtViaLoginError()
    {
        $email = 'test@email.com';
        $pass = '123456';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(500)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtViaLogin(
            $email,
            $pass
        );
    }

    /**
     * @covers \App\Services\ExternalApiService::validJwt
     */
    public function testValidJwtEmpty()
    {
        $jwtData = [];

        $guzzleSpy = Mockery::spy(Client::class);

        $service = new ExternalApiService($guzzleSpy);
        $response = $service->validJwt(
            $jwtData
        );

        $this->assertInternalType('bool', $response);
        $this->assertEquals(false, $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::validJwt
     */
    public function testValidJwtFalse()
    {
        $jwtData = [
            'jwt' => 'abc',
            'valid_until' => '2018-11-17 15:17:00',
        ];

        $guzzleSpy = Mockery::spy(Client::class);

        $service = new ExternalApiService($guzzleSpy);
        $response = $service->validJwt(
            $jwtData
        );

        $this->assertInternalType('bool', $response);
        $this->assertEquals(false, $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::validJwt
     */
    public function testValidJwtTrue()
    {
        $now = date('Y-m-d H:i:s');
        $validDate = date('Y-m-d H:i:s', strtotime('+2 minutes', strtotime($now)));
        $jwtData = [
            'jwt' => 'abc',
            'valid_until' => $validDate,
        ];

        $guzzleSpy = Mockery::spy(Client::class);

        $service = new ExternalApiService($guzzleSpy);
        $response = $service->validJwt(
            $jwtData
        );

        $this->assertInternalType('bool', $response);
        $this->assertEquals(true, $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::requestApi
     */
    public function testRequestApi()
    {
        $cliCod = 123;
        $email = 'test@email.com';
        $apiKey = '1a2b3c4d5e';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->twice()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->twice()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->twice()
            ->withNoArgs()
            ->andReturn(json_encode([]))
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->requestApi(
            $cliCod,
            $email,
            $apiKey,
            'POST',
            'test'
        );
        $this->assertInternalType('array', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::requestApi
     * @expectedException App\Exceptions\Custom\InvalidCredentialsException
     */
    public function testRequestApiCredentials()
    {
        $cliCod = 123;
        $email = 'test@email.com';
        $apiKey = '1a2b3c4d5e';
        $apiConf = config('externalapi');

        $json = '{"data":{"token":"abc"}}';

        $params = [
            'POST',
            $apiConf['url'].'test',
            [
                'headers' => [
                    'token' => 'abc',
                ],
                'form_params' => [],
                'http_errors' => false,
            ]
        ];

        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();
        
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($params)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(401);

        $service = new ExternalApiService($guzzleMock);
        $response = $service->requestApi(
            $cliCod,
            $email,
            $apiKey,
            'POST',
            'test'
        );
    }

        /**
     * @covers \App\Services\ExternalApiService::requestApi
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testRequestApiError()
    {
        $cliCod = 123;
        $email = 'test@email.com';
        $apiKey = '1a2b3c4d5e';
        $apiConf = config('externalapi');

        $json = '{"data":{"token":"abc"}}';

        $params = [
            'POST',
            $apiConf['url'].'test',
            [
                'headers' => [
                    'token' => 'abc',
                ],
                'form_params' => [],
                'http_errors' => false,
            ]
        ];

        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();
        
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($params)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(500);

        $service = new ExternalApiService($guzzleMock);
        $response = $service->requestApi(
            $cliCod,
            $email,
            $apiKey,
            'POST',
            'test'
        );
    }

    /**
     * @covers \App\Services\ExternalApiService::loginApi
     */
    public function testLoginApi()
    {
        $email = 'test@email.com';
        $pass = '123456';

        $result = [
            'data' => [
                [
                    'user_id' => 123,
                    'api_key' => 'a1b2c3d4e5',
                ]
            ],
        ];

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->twice()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->twice()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->twice()
            ->withNoArgs()
            ->andReturn(json_encode($result))
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->loginApi(
            $email,
            $pass
        );
        $this->assertInternalType('array', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::loginApi
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testLoginApiEmpty()
    {
        $email = 'test@email.com';
        $pass = '123456';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->twice()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->twice()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->twice()
            ->withNoArgs()
            ->andReturn(json_encode([]))
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->loginApi(
            $email,
            $pass
        );
        $this->assertInternalType('array', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::loginApi
     * @expectedException App\Exceptions\Custom\InvalidCredentialsException
     */
    public function testLoginApiCredentials()
    {
        $email = 'test@email.com';
        $pass = '456123';
        $apiConf = config('externalapi');

        $json = '{"data":{"token":"abc"}}';

        $params = [
            'GET',
            $apiConf['url'].'user/get_me',
            [
                'headers' => [
                    'token' => 'abc',
                ],
                'http_errors' => false,
            ]
        ];

        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();
        
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($params)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(401);

        $service = new ExternalApiService($guzzleMock);
        $response = $service->loginApi(
            $email,
            $pass
        );
    }

        /**
     * @covers \App\Services\ExternalApiService::loginApi
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testLoginApiError()
    {
        $email = 'test@email.com';
        $pass = '456123';
        $apiConf = config('externalapi');

        $json = '{"data":{"token":"abc"}}';

        $params = [
            'GET',
            $apiConf['url'].'user/get_me',
            [
                'headers' => [
                    'token' => 'abc',
                ],
                'http_errors' => false,
            ]
        ];

        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();
        
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($params)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(500);

        $service = new ExternalApiService($guzzleMock);
        $response = $service->loginApi(
            $email,
            $pass
        );
    }


    /**
     * @covers \App\Services\ExternalApiService::getJwtPartnerViaCredentials
     */
    public function testGetJwtPartnerViaCredentials()
    {
        $json = '{"data":{"token":"abc"}}';

        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtPartnerViaCredentials();
        $this->assertInternalType('string', $response);
        $this->assertEquals('abc', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::getJwtPartnerViaCredentials
     * @expectedException App\Exceptions\Custom\InvalidCredentialsException
     */
    public function testGetJwtPartnerViaCredentialsInvalid()
    {
        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(401)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtPartnerViaCredentials();
    }

        /**
     * @covers \App\Services\ExternalApiService::getJwtPartnerViaCredentials
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testGetJwtPartnerViaCredentialsError()
    {
        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(500)
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->getJwtPartnerViaCredentials();
    }

    /**
     * @covers \App\Services\ExternalApiService::requestApiPartner
     */
    public function testRequestApiPartner()
    {
        $guzzleMock = Mockery::mock(Client::class)
            ->shouldReceive('request')
            ->twice()
            ->withAnyArgs()
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->twice()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->twice()
            ->withNoArgs()
            ->andReturn(json_encode([]))
            ->getMock();

        $service = new ExternalApiService($guzzleMock);
        $response = $service->requestApiPartner(
            'POST',
            'test'
        );
        $this->assertInternalType('array', $response);
    }

    /**
     * @covers \App\Services\ExternalApiService::requestApiPartner
     * @expectedException App\Exceptions\Custom\InvalidCredentialsException
     */
    public function testRequestApiParnterCredentials()
    {
        $apiConf = config('externalapi');

        $json = '{"data":{"token":"abc"}}';

        $params = [
            'POST',
            $apiConf['url'].'partner/test',
            [
                'headers' => [
                    'token' => 'abc',
                ],
                'form_params' => [],
                'http_errors' => false,
            ]
        ];

        $firstRequest = [
            'POST',
            $apiConf['url'].'credential/generate_partner_token',
            [
                'form_params' => [
                    'partner_token' => $apiConf['token'],
                    'partner_secret' => $apiConf['secret'],
                ],
                'http_errors' => false,
            ]
        ];

        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($firstRequest)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json);
        
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($params)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(401)
            ->getMock();
        
        $service = new ExternalApiService($guzzleMock);
        $response = $service->requestApiPartner(
            'POST',
            'test'
        );
    }

    /**
     * @covers \App\Services\ExternalApiService::requestApiPartner
     * @expectedException App\Exceptions\Custom\ExternalApiException
     */
    public function testRequestApiParnterError()
    {
        $apiConf = config('externalapi');

        $json = '{"data":{"token":"abc"}}';

        $params = [
            'POST',
            $apiConf['url'].'partner/test',
            [
                'headers' => [
                    'token' => 'abc',
                ],
                'form_params' => [],
                'http_errors' => false,
            ]
        ];

        $firstRequest = [
            'POST',
            $apiConf['url'].'credential/generate_partner_token',
            [
                'form_params' => [
                    'partner_token' => $apiConf['token'],
                    'partner_secret' => $apiConf['secret'],
                ],
                'http_errors' => false,
            ]
        ];

        $guzzleMock = Mockery::mock(Client::class);
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($firstRequest)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(200)
            ->shouldReceive('getBody')
            ->once()
            ->withNoArgs()
            ->andReturn($json);
        
        $guzzleMock->shouldReceive('request')
            ->once()
            ->withArgs($params)
            ->andReturnSelf()
            ->shouldReceive('getStatusCode')
            ->once()
            ->withNoArgs()
            ->andReturn(500)
            ->getMock();
        
        $service = new ExternalApiService($guzzleMock);
        $response = $service->requestApiPartner(
            'POST',
            'test'
        );
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
