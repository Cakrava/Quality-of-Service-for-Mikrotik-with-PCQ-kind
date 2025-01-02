<?php

namespace App\Http\Controllers;

use App\Models\RouterosApi;
use Illuminate\Http\Request;

class DataController extends Controller
{

    public function data()
    {
        $ip = 'id-29.hostddns.us:8381';
        $user = 'admin';
        $password = 'moko';
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            $address = $API->comm('/ip/address/print');

            $data = [
                'address' => $address,
            ];

            session()->put('active_page', 'simple_queue');
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Gagal terhubung ke MikroTik']);
        }
    }
}
