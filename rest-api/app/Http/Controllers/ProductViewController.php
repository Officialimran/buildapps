<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductViewController extends Controller
{
    public function index()
    {
        $client             = new \GuzzleHttp\Client();
        $url                = 'http://127.0.0.1:8000/api/login';
        $data['email']      = 'imran@gmail.com';
        $data['password']   = '123456';
        $request = $client->post($url, [
            'form_parms'    => $data
        ]);

        $response = $request->getBody();
        $info = json_decode($response, true);
        dd($response);
    }
}
