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



    // Fungsi untuk mengonversi uptime (XhYmZs) ke detik
    private function uptimeToSeconds($uptime)
    {
        if (empty($uptime)) {
            return 0; // Kembalikan 0 jika uptime kosong
        }

        // Ekstrak jam, menit, dan detik dari string uptime
        preg_match('/(\d+)h/', $uptime, $hoursMatch);
        preg_match('/(\d+)m/', $uptime, $minutesMatch);
        preg_match('/(\d+)s/', $uptime, $secondsMatch);

        $hours = $hoursMatch[1] ?? 0;
        $minutes = $minutesMatch[1] ?? 0;
        $seconds = $secondsMatch[1] ?? 0;

        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    // Fungsi untuk mengonversi detik ke format HH:MM:SS
    private function secondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
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

    public function fetchAllData()
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

    public function fetchSystemStats(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if (!$API->connect($ip, $user, $password)) {
            return response()->json(['error' => 'Failed to connect to MikroTik'], 500);
        }

        // Ambil data resource (memory, CPU, uptime)
        $resource = $API->comm('/system/resource/print');

        if (empty($resource)) {
            return response()->json(['error' => 'Failed to fetch system resource'], 500);
        }

        // Hitung persentase memory usage
        $totalMemory = $resource[0]['total-memory'];
        $freeMemory = $resource[0]['free-memory'];
        $usedMemory = $totalMemory - $freeMemory;
        $memoryUsage = ($usedMemory / $totalMemory) * 100;

        // Ambil CPU usage
        $cpuUsage = $resource[0]['cpu-load'];

        // Ambil uptime dan hitung persentase dalam 24 jam
        $uptime = $resource[0]['uptime'];
        $uptimeInSeconds = $this->uptimeToSeconds($uptime);
        $uptimePercentage = ($uptimeInSeconds / (24 * 3600)) * 100; // Persentase dalam 24 jam

        // Format uptime ke HH:MM:SS
        $uptimeFormatted = $this->secondsToTime($uptimeInSeconds);

        // Ambil detail tambahan
        $cpuCount = $resource[0]['cpu-count']; // Jumlah core CPU
        $cpuFrequency = $resource[0]['cpu-frequency']; // Frekuensi CPU
        $cpuName = $resource[0]['cpu']; // Nama atau model CPU
        $buildTime = $resource[0]['build-time']; // Waktu build RouterOS

        // Kembalikan response dengan system stats
        return response()->json([
            'memoryUsage' => round($memoryUsage, 2), // Memory usage dalam persen
            'totalMemory' => $totalMemory, // Total memory dalam bytes
            'freeMemory' => $freeMemory, // Free memory dalam bytes
            'usedMemory' => $usedMemory, // Used memory dalam bytes
            'cpuUsage' => (float)$cpuUsage, // CPU usage dalam persen
            'cpuCount' => $cpuCount, // Jumlah core CPU
            'cpuFrequency' => $cpuFrequency, // Frekuensi CPU dalam MHz
            'cpuName' => $cpuName, // Nama atau model CPU
            'uptimePercentage' => round($uptimePercentage, 2), // Uptime dalam persen (24 jam)
            'uptimeFormatted' => $uptimeFormatted, // Uptime dalam format HH:MM:SS
            'buildTime' => $buildTime, // Waktu build RouterOS
        ]);
    }


    public function fetchTrafficData(Request $request)
    {
        $startTime = microtime(true);

        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');

        // Buat koneksi API terpisah untuk /tool/torch
        $torchAPI = new RouterosApi();
        $torchAPI->debug = false;

        if (!$torchAPI->connect($ip, $user, $password)) {
            error_log('Gagal terhubung ke MikroTik untuk /tool/torch');
            return response()->json(['error' => 'Failed to connect to MikroTik'], 500);
        }

        // Ambil data interfaces
        $interfaces = $torchAPI->comm('/interface/print');
        error_log('Data interfaces: ' . print_r($interfaces, true));

        // Ambil ID interface yang dipilih dari request
        $interfaceId = $request->input('interface');
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
        $torch = $torchAPI->comm('/tool/torch', [
            'interface' => $interfaceName,
            'duration' => '2', // Durasi dikurangi
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
        $dhcpLeases = $torchAPI->comm('/ip/dhcp-server/lease/print');
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

        // Kembalikan response dengan data interfaces dan traffic
        $response = response()->json([
            'interfaces' => $interfaces,
            'traffic' => array_values($aggregatedData)
        ]);

        $endTime = microtime(true);
        $executionTime = $endTime - $startTime;
        error_log('Waktu eksekusi: ' . $executionTime . ' detik');

        // Tutup koneksi API untuk /tool/torch
        $torchAPI->disconnect();

        return $response;
    }
}
