<?php

namespace App\Services;


class SvcRequest
{
    public function requestApis($urlRecurso, $body = [], $tipo = 'POST', $token = '')
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $urlRecurso);
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, ['token: '. session()->get('token')]
        );
        
        if ($tipo === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
        if ($tipo === 'GET') {
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $respuesta = curl_exec($ch);
        curl_close($ch);
        
        return $respuesta;
    }
}