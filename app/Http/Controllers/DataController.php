<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterosApi;

class DataController extends Controller
{
    // Fungsi utama untuk mengambil data system stats
    public function data(Request $request)
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

        // Ambil ID interface yang dipilih dari request
        $interfaceId = $request->input('interface');
        $interfaces = $API->comm('/interface/print');
        $selectedInterface = collect($interfaces)->first(function ($interface) use ($interfaceId) {
            return $interface['.id'] === $interfaceId;
        });

        if (!$selectedInterface) {
            error_log('Interface tidak ditemukan: ' . $interfaceId);
            return response()->json(['error' => 'Interface dengan ID ' . $interfaceId . ' tidak ditemukan'], 404);
        }

        $interfaceName = $selectedInterface['name'];
        error_log('Interface yang dipilih: ' . $interfaceName);

        $aggregatedData = [];

        // Ambil data traffic menggunakan torch
        $torch = $API->comm('/tool/torch', [
            'interface' => $interfaceName,
            'duration' => '1', // Durasi dikurangi
            'src-address' => '0.0.0.0/0',
            'dst-address' => '0.0.0.0/0',
        ]);
        error_log('Data torch: ' . print_r($torch, true));

        foreach ($torch as $entry) {
            if (isset($entry['mac-protocol'], $entry['src-address'], $entry['tx'], $entry['rx'])) {
                $src = $entry['src-address'];

                if (!isset($aggregatedData[$src])) {
                    $aggregatedData[$src] = [
                        'mac-protocol' => $entry['mac-protocol'],
                        'src-address' => $src,
                        'tx-total' => 0,
                        'rx-total' => 0,
                        'interface' => $interfaceName,
                        'mac-address' => null,
                        'hostname' => null,
                    ];
                }

                $aggregatedData[$src]['tx-total'] += (int)$entry['tx'];
                $aggregatedData[$src]['rx-total'] += (int)$entry['rx'];
            }
        }

        // Ambil semua DHCP lease sekaligus
        $dhcpLeases = $API->comm('/ip/dhcp-server/lease/print');
        error_log('Data DHCP leases: ' . print_r($dhcpLeases, true));

        $dhcpLeasesMap = [];
        foreach ($dhcpLeases as $lease) {
            $dhcpLeasesMap[$lease['address']] = $lease;
        }

        // Tambahkan data dari DHCP lease
        foreach ($aggregatedData as $srcIp => &$entry) {
            if (isset($dhcpLeasesMap[$srcIp])) {
                $entry['mac-address'] = $dhcpLeasesMap[$srcIp]['mac-address'];
                $entry['hostname'] = $dhcpLeasesMap[$srcIp]['host-name'];
            }
        }

        // Kembalikan response dengan data traffic
        $response = response()->json([
            'traffic' => array_values($aggregatedData)
        ]);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        error_log('Waktu eksekusi: ' . $executionTime . ' detik');

        return $response;
    }
}
