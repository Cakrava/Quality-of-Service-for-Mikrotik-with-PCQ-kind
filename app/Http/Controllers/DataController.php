<?php

namespace App\Http\Controllers;

use App\Models\RouterosApi;
use Illuminate\Http\Request;

class DataController extends Controller
{

    public function data($interfaceId)
    {
        $ip = '192.168.1.108';
        $user = 'admin';
        $password = 'admin';
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Mendapatkan daftar interface
            $interfaces = $API->comm('/interface/print');

            // Mencari interface berdasarkan ID
            $selectedInterface = collect($interfaces)->first(function ($interface) use ($interfaceId) {
                return $interface['.id'] === $interfaceId;
            });

            if (!$selectedInterface) {
                return response()->json(['error' => 'Interface dengan ID ' . $interfaceId . ' tidak ditemukan']);
            }

            $interfaceName = $selectedInterface['name'];
            $aggregatedData = [];

            // Mengambil data dari torch untuk interface ini
            $torch = $API->comm('/tool/torch', [
                'interface' => $interfaceName,
                'duration' => '5',
                'src-address' => '0.0.0.0/0',
                'dst-address' => '0.0.0.0/0',
            ]);

            foreach ($torch as $entry) {
                if (isset(
                    $entry['mac-protocol'],
                    $entry['src-address'],
                    $entry['tx'],
                    $entry['rx']
                )) {
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

            // Tambahkan data dari DHCP lease
            foreach ($aggregatedData as $srcIp => &$entry) {
                $dhcpLease = $API->comm('/ip/dhcp-server/lease/print', [
                    '?address' => $srcIp
                ]);

                if (!empty($dhcpLease)) {
                    $entry['mac-address'] = $dhcpLease[0]['mac-address'];
                    $entry['hostname'] = $dhcpLease[0]['host-name'];
                }
            }

            return response()->json(['torch' => array_values($aggregatedData)]);
        } else {
            return response()->json(['error' => 'Gagal terhubung ke MikroTik']);
        }
    }

    private function fetchTrafficUsageByInterface($interfaceId)
    {
        $ip = '192.168.1.108';
        $user = 'admin';
        $password = 'admin';
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Mendapatkan daftar interface
            $interfaces = $API->comm('/interface/print');

            // Cek apakah interfaceId ada dalam daftar interface
            $selectedInterface = collect($interfaces)->first(function ($interface) use ($interfaceId) {
                return $interface['.id'] === $interfaceId; // Membandingkan ID dengan yang diterima
            });

            if (!$selectedInterface) {
                return response()->json(['error' => 'Interface dengan ID ' . $interfaceId . ' tidak ditemukan']);
            }

            $interfaceName = $selectedInterface['name'];
            $aggregatedData = [];

            // Mengambil data dari torch untuk interface ini
            $torch = $API->comm('/tool/torch', [
                'interface' => $interfaceName,
                'duration' => '5',
                'src-address' => '0.0.0.0/0',
                'dst-address' => '0.0.0.0/0',
            ]);

            foreach ($torch as $entry) {
                if (isset(
                    $entry['mac-protocol'],
                    $entry['src-address'],
                    $entry['tx'],
                    $entry['rx']
                )) {
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

            // Tambahkan data dari DHCP lease
            foreach ($aggregatedData as $srcIp => &$entry) {
                $dhcpLease = $API->comm('/ip/dhcp-server/lease/print', [
                    '?address' => $srcIp
                ]);

                if (!empty($dhcpLease)) {
                    $entry['mac-address'] = $dhcpLease[0]['mac-address'];
                    $entry['hostname'] = $dhcpLease[0]['host-name'];
                }
            }

            return response()->json(['torch' => array_values($aggregatedData)]);
        } else {
            return response()->json(['error' => 'Gagal terhubung ke MikroTik']);
        }
    }
}
