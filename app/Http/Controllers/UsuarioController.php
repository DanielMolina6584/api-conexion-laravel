<?php

namespace App\Http\Controllers;

use App\Services\SvcRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UsuarioController extends Controller
{
    public function __construct()
    {

    }
    public function index(Request $request)
    {
        $curl = new SvcRequest();
        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/listado';

        $respuesta = json_decode($curl->requestApis($url, [], 'GET'), true);

        return view('usuario', ['respuesta' => $respuesta]);
    }
    // CREAR USUARIO
    public function crearUsuario(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'cel' => 'required|numeric',
            'image' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Los datos no son correctos',
                'errors' => $validator->errors(),
            ]);
        }

        $curl = new SvcRequest();

        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/admin/crear';

        $imageName = $request->file('image')->getClientOriginalName();
    
        $imageUrl = public_path('uploads');
    
        $request->file('image')->move($imageUrl, $imageName);
    
        $UrlCompleta = $imageUrl . '/' . $imageName;
    
        $image = new \CURLFile($UrlCompleta, mime_content_type($UrlCompleta), $imageName);
    
        $data = [
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'email' => $request->input('email'),
            'cel' => $request->input('cel'),
            'image' =>  $image,
        ];
        $respuesta = $curl->requestApis($url, $data);
        return response()->json([$respuesta]);
    }


    // ELIMINAR
    public function eliminarUsuario(Request $request)
    {
        $curl = new SvcRequest();
        $id = $request->input('id');

        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/admin/eliminar?id=' .$id;

        $respuesta = $curl->requestApis($url, [], 'GET');
        return response()->json([$respuesta]);
    }




    //OBTENER USUARIO
    public function obtenerIdUsuario(Request $request){
        $curl = new SvcRequest();
        $id = $request->input('id');
        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/listadoId?id='.$id;

        $respuesta = json_decode($curl->requestApis($url, [], 'GET'), true);

        return view('actualizar', ['respuesta' => $respuesta]);

    }

    //ACTUALIZAR
    public function actualizarUsuario(Request $request){
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email',
            'cel' => 'required|numeric',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Los datos no son válidos',
                'errors' => $validator->errors(),
            ]);
        }

        $curl = new SvcRequest();
        $url = 'http://localhost/UsersCrud/CodeIgnaiter/public/admin/actualizar';

        $imageName = $request->file('image')->getClientOriginalName();
    
        $imageUrl = public_path('uploads');
    
        $request->file('image')->move($imageUrl, $imageName);
    
        $UrlCompleta = $imageUrl . '/' . $imageName;
    
        $image = new \CURLFile($UrlCompleta, mime_content_type($UrlCompleta), $imageName);
        
        $data = [
            'id' => $request->input('id'),
            'nombre' => $request->input('nombre'),
            'apellido' => $request->input('apellido'),
            'email' => $request->input('email'),
            'cel' => $request->input('cel'),
            'image' => $image,
        ];

        $respuesta = $curl->requestApis($url, $data);
        
        return response()->json([$respuesta]);
    }
    
}
