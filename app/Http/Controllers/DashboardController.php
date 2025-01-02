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



    // ambil data realtime
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
