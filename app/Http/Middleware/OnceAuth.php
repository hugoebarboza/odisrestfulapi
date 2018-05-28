<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Helpers\JwtAuth;


class OnceAuth 
{
    /**
     * The guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(AuthFactory $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        //return $this->auth->guard($guard)->basic() ?: $next($request);
        $hash = $request-> header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth -> checkToken($hash);

        if($checkToken){
            //echo "User autenticado";
            return $next($request);
        }else{
            $data = array(
                'status' => 'error',
                'message'=> 'No se puede procesar transaccion, Usuario no autenticado', 
                'code' => 401
                );
        }
        return response()->json($data, 200);            
    }
}
