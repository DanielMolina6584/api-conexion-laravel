<?php

namespace App\Http\Middleware;

use App\Services\SvcRequest;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\ResponseInterface;


class Token
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $svcRequest = new SvcRequest();

        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/token';

        $token = $request->header('token');
        $data = [
            'token'=> $token,
        ];

        $response = json_decode($svcRequest->requestApis($url, $data, 'POST', $token), true);

    if ($response['status'] == 'true') {
            session()->put('token', $token);
        } else {
            session()->forget('token');
            
            $errors = 'Token Invalido';
            $errorMessages = [];
            $errorMessages[] = $errors;
            
           return response()->json([
                "error"  => 'NO AUTORIZADO',
                "errors" => $errorMessages,                    
            ]);
            
        }
        
      return $next($request);
    }
}
