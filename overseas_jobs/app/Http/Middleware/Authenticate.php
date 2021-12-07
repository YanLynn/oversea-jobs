<?php
namespace App\Http\Middleware;
use Closure;
use JWTAuth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
class Authenticate extends Middleware
{
    // Override handle method
    public function handle($request, Closure $next, ...$guards)
    {
        try {
                JWTAuth::parseToken()->authenticate();
            } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['error'=>'token is expired'],401);
                // do whatever you want to do if a token is expired

            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['error'=>'token is invalid'],401);
                // do whatever you want to do if a token is invalid

            } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['error'=>'token is not present'],401);
                // do whatever you want to do if a token is not present
            }
        if ($this->authenticate($request, $guards) === 'authentication_failed') {
            return response()->json(['error'=>'Unauthorized'],401);
        }
        return $next($request);
    }
    // Override authentication method
    protected function authenticate($request, array $guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }
        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }
        return 'authentication_failed';
    }
}