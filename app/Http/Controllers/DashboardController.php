<?php
// Controller to display active interfaces on MikroTik
namespace App\Http\Controllers;

use App\Models\RouterosApi;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Ambil data interface
            $interface = $API->comm('/interface/print');

            // Ambil data sistem
            $system = $API->comm('/system/identity/print');

            // Ambil data simple queue
            $simpleQueue = $API->comm('/queue/simple/print');

            // Ambil data queue list
            $queueList = $API->comm('/queue/print');

            // Ambil data access list
            $accessList = $API->comm('/interface/wireless/registration-table/print');
            $queueType = $API->comm('/queue/type/print', ['?kind' => 'pcq']);
            $dns = $API->comm('/dns/static/print');
            $address = $API->comm('/ip/address/print');

            $data = [
                'interfaces' => $interface,
                'system' => $system,
                'simpleQueue' => $simpleQueue,
                'queueList' => $queueList,
                'accessList' => $accessList,
                'queueType' => $queueType,
                'dns' => $dns,
                'address' => $address,
            ];

            session()->put('active_page', 'dashboard');
            return view('dashboard.dashboard', $data);
        } else {
            return redirect('auth.login');
        }
    }

    public function updateInterfaceName(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;  // Matikan debugging agar tidak ada print yang muncul
        $API->timeout = 10;  // Atur timeout untuk koneksi yang lebih lama jika diperlukan

        // Validasi input
        $request->validate([
            'interfaceName' => 'required|string',
            'newInterfaceName' => 'required|string',
        ]);

        if ($API->connect($ip, $user, $password)) {
            // Update nama interface
            $currentName = $request->input('interfaceName');
            $newName = $request->input('newInterfaceName');

            // Mengambil list interface dan menemukan ID yang sesuai tanpa mencetak hasil
            $interfaces = $API->comm('/interface/print');
            $interfaceId = null;

            foreach ($interfaces as $interface) {
                if (isset($interface['name']) && $interface['name'] == $currentName) {
                    $interfaceId = $interface['.id'];
                    break;
                }
            }

            if ($interfaceId) {
                // Mengubah nama interface menggunakan ID yang ditemukan
                $API->comm('/interface/set', [
                    '.id' => $interfaceId,
                    'name' => $newName,
                ]);

                // Redirect kembali ke halaman dashboard dengan pesan sukses
                return redirect()->route('dashboard.dashboard')->with('success', "Nama interface '$currentName' berhasil diubah menjadi '$newName'.");
            } else {
                // Jika interface tidak ditemukan
                return redirect()->route('dashboard.dashboard')->with('error', "Interface dengan nama '$currentName' tidak ditemukan.");
            }
        } else {
            // Redirect dengan pesan error jika koneksi gagal
            return redirect()->route('dashboard.dashboard')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }


    public function fetchAllData(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if (!$API->connect($ip, $user, $password)) {
            return response()->json(['error' => 'Failed to connect to MikroTik'], 500);
        }

        // Ambil data interfaces
        $interfaces = $API->comm('/interface/print');

        // Ambil ID interface yang dipilih dari request
        $interfaceId = $request->input('interface');
        $selectedInterface = collect($interfaces)->first(function ($interface) use ($interfaceId) {
            return $interface['.id'] === $interfaceId;
        });

        if (!$selectedInterface) {
            return response()->json(['error' => 'Interface dengan ID ' . $interfaceId . ' tidak ditemukan'], 404);
        }

        $interfaceName = $selectedInterface['name'];
        $aggregatedData = [];

        // Ambil data traffic menggunakan torch
        $torch = $API->comm('/tool/torch', [
            'interface' => $interfaceName,
            'duration' => '2',
            'src-address' => '0.0.0.0/0',
            'dst-address' => '0.0.0.0/0',
        ]);

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


        // Kembalikan response dengan data interfaces dan traffic
        return response()->json([
            'interfaces' => $interfaces,
            'traffic' => array_values($aggregatedData)
        ]);
    }

    public function fetchInterfaces()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            $interfaces = $API->comm('/interface/print');
            return response()->json(['interfaces' => $interfaces]);
        } else {
            return response()->json(['error' => 'Failed to connect to MikroTik'], 500);
        }
    }
}
