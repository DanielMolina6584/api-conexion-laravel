<?php

namespace App\Http\Controllers;

use App\Services\SvcRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view("login");
    }
    public function autenticar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Los datos no son válidos',
                'errors' => $validator->errors(),
            ]);
        }


        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/iniciar';

        $data = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        $curl = new SvcRequest();

        $respuesta = json_decode($curl->requestApis($url, $data, 'POST'), true);

        if (isset($respuesta['token']) ?? '') {
            session()->put('token', $respuesta['token']);
            return response()->json([
                'token' => $respuesta['token'],
                'success' => true,
            ]);
        }
    }

    public function logout(Request $request)
    {
        session()->forget('token');
    }
   
}





