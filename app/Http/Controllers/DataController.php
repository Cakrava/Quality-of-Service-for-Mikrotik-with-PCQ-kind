<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterosApi;

class DataController extends Controller
{
    public function fetdachAllData()
    {
        $startTime = microtime(true);

        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if (!$API->connect($ip, $user, $password)) {
            error_log('Gagal terhubung ke MikroTik');
            return response()->json(['error' => 'Failed to connect to MikroTik'], 500);
        }

        // Ambil data interfaces
        $interfaces = $API->comm('/interface/print');
        error_log('Data interfaces: ' . print_r($interfaces, true));

        // Kembalikan response dengan data interfaces
        $response = response()->json([
            'interfaces' => $interfaces
        ]);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        error_log('Waktu eksekusi: ' . $executionTime . ' detik');

        return $response;
    }
}
