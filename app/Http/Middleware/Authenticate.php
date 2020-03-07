<?php

namespace App\Http\Middleware;

use App\Exceptions\Custom\EmptyCredentialsException;
use App\Exceptions\Custom\ForbiddenAccessException;
use App\Exceptions\Custom\InvalidCredentialsException;
use App\Exceptions\Custom\MissingRouteAliasException;
use App\Helpers\ResponseJsonHelper;
use Closure;

class Authenticate
{
    /**
     * Search on config for token and secret to authenticate
     * @param string $token
     * @param string $secret
     * @return string|null
     */
    private function getFromToken(
        string $token,
        string $secret
    ) :? array {
        $tokens = config('token.tokens');
        if (array_key_exists($token, $tokens) &&
            $tokens[$token]['secret'] == $secret
        ) {
            return $tokens[$token];
        }
        return null;
    }

    /**
     * Auth from token and client id
     * @param string $token token
     * @param string $secret secret
     * @param int $cliCod client id
     * @param string $route current route
     * @throws InvalidCredentialsException
     * @throws ForbiddenAccessException
     * @return string
     */
    private function authFromTokenAndSecret(
        string $token,
        string $secret,
        string $route
    ) {
        $user = $this->getFromToken($token, $secret);
        if (empty($user)) {
            throw new InvalidCredentialsException();
        }

        if (!in_array($route, $user['routes']) && !in_array('all', $user['routes'])) {
            throw new ForbiddenAccessException();
        }

        return $user['name'];
    }
   
    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @throws EmptyCredentialsException
     * @throws MissingRouteAliasException
     * @throws MissingRouteJobException
     * @throws MissingRouteValidateException
     * @return void
     */
    public function handle(
        $request,
        Closure $next
    ) {
        ResponseJsonHelper::setStartProfiler();

        $request->id = ResponseJsonHelper::$requestId;

        $token = $request->headers->get('token') ?? null;
        $secret = $request->headers->get('secret') ?? null;

        if (empty($token) || empty($secret)) {
            throw new EmptyCredentialsException();
        }

        $route = $request->route()[1]['as'] ?? null;
        if (empty($route)) {
            throw new MissingRouteAliasException();
        }

        $request->user = $this->authFromTokenAndSecret(
            $token,
            $secret,
            $route
        );

        $request->token = $token;

        $request->validate = $request->route()[1]['validate'] ?? null;
        $request->refiner = $request->route()[1]['refiner'] ?? null;

        $request->info = config('version.info');

        return $next($request);
    }
}
