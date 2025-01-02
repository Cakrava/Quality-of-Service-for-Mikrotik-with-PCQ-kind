<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterosApi;

class NetworkController extends Controller
{
    protected $user;
    protected $password;
    protected $routerosApi;

    public function __construct()
    {
        $this->user = session('user');
        $this->password = session('password');

        if (!empty($this->user) && !empty($this->password)) {
            $this->routerosApi = new RouterosApi($this->user, $this->password);
        } else {
            $this->routerosApi = null;
        }
    }
    // address

    public function ipAddress()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            $simpleQueue = $API->comm('/queue/simple/print');
            $interface = $API->comm('/interface/print');
            $address = $API->comm('/ip/address/print');
            $queueType = $API->comm('/queue/type/print');
            $simpleQueueType = $API->comm('/queue/simple/type/print');

            $data = [
                'simpleQueue' => $simpleQueue,
                'interface' => $interface,
                'address' => $address,
                'queueType' => $queueType,
                'simpleQueueType' => $simpleQueueType,
            ];

            session()->put('active_page', 'address');
            return view('Network.address', $data);
        } else {
            return redirect('failed');
        }
    }
    public function saveAddress(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;  // Matikan debugging agar tidak ada print yang muncul
        $API->timeout = 10;   // Atur timeout untuk koneksi yang lebih lama jika diperlukan

        // Validasi input
        $request->validate([
            'ipAddress' => 'required|string',
            'interface' => 'required|string',
            'network' => 'required|string',
        ]);

        // Pastikan input valid
        $ipAddress = $request->input('ipAddress');
        $interface = $request->input('interface');
        $network = $request->input('network');
        $id = $request->input('hiddenId');  // ID queue yang diterima untuk update, jika ada

        // Memastikan koneksi API MikroTik
        if ($API->connect($ip, $user, $password)) {
            if (empty($id)) {
                // Jika ID kosong, berarti akan melakukan save (tambah data baru)
                // Untuk PCQ Queue
                $API->comm('/ip/address/add', [
                    'address' => $ipAddress,                // Nama Queue
                    'interface' => $interface,                    // Jenis queue (PCQ)
                    'network' => $network,  // Classifier (src-address atau dst-address)
                ]);


                // Redirect dengan pesan sukses
                return redirect()->route('network.address')->with('success', "Queue '$ipAddress' berhasil ditambahkan.");
            } else {
                // Jika ID ada, berarti akan melakukan update
                // Update PCQ Queue Type
                $API->comm('/ip/address/set', [
                    'numbers' => $id,                  // ID Queue yang akan diupdate
                    'address' => $ipAddress,                    // Nama Queue
                    'interface' => $interface,                         // Jenis queue (PCQ)
                    'network' => $network,                  // Rate untuk PCQ
                    // Classifier (src-address atau dst-address)
                ]);

                // Update Simple Queue yang terhubung dengan PCQ
                $API->comm('/ip/address/set', [
                    'numbers' => $id,                    // ID Queue yang ditemukan
                    'address' => $ipAddress,
                    'network' => $network,              // Nama Queue
                    'interface' => $interface,               // Classifier (src-address atau dst-address)

                ]);

                // Redirect dengan pesan sukses
                return redirect()->route('network.address')->with('success', "Address '$ipAddress' berhasil diperbarui.");
            }
        } else {
            // Jika koneksi gagal
            return redirect()->route('network.address')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }
    public function deleteAddress(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        // Ambil hiddenId dari request
        $deleteAddress = $request->input('deleteAddress');
        $id = $request->input('deleteID'); // Menangkap ID yang dikirim dari form

        // Cek koneksi ke MikroTik
        if ($API->connect($ip, $user, $password)) {
            // Pastikan ID valid
            if (!empty($id)) {
                // Mengirim perintah untuk menghapus alamat IP berdasarkan ID
                $API->write('/ip/address/remove', false);
                $API->write('=.id=' . $id);
                $API->read(); // Membaca response dari MikroTik
            }

            $API->disconnect();

            // Redirect dengan pesan sukses
            return redirect()->route('network.address')->with('success', 'Address ' . $id . ' berhasil dihapus.');
        } else {
            // Jika gagal terkoneksi ke MikroTik
            return redirect()->route('network.address')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }



    public function clientList()
    {
        $ip = 'id-29.hostddns.us:8381';
        $user = 'admin';
        $password = 'moko';
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Ambil daftar lease DHCP
            $leases = $API->comm('/ip/dhcp-server/lease/print');
            // Ambil daftar alamat IP
            $address = $API->comm('/ip/address/print');
            $interface = $API->comm('/interface/print');
            $queueType = $API->comm('/queue/type/print');
            $simpleQueue = $API->comm('/queue/simple/print');
            // Menyimpan interface target
            $targetInterface = null;
            $targetIp = null;

            // Memeriksa setiap alamat IP yang terkonfigurasi di MikroTik
            foreach ($address as $address) {
                if (isset($address['network']) && isset($address['interface'])) {
                    $networkIp = $address['network']; // Mengambil alamat network

                    foreach ($leases as $lease) {
                        if (isset($lease['address'])) {
                            $targetIp = $lease['address'];
                            $targetHostName = $lease['host-name'];

                            // Simpan alamat sumber ke dalam file JSON lokal

                            // Bandingkan tiga oktet pertama dari lease IP dan network IP
                            $leaseIpPrefix = implode('.', array_slice(explode('.', $targetIp), 0, 3));
                            $networkIpPrefix = implode('.', array_slice(explode('.', $networkIp), 0, 3));

                            if ($leaseIpPrefix === $networkIpPrefix) {
                                $targetInterface = $address['interface'];

                                break 2; // Keluar dari kedua loop
                            }
                        }
                    }
                }
            }
            $existingData = json_decode(file_get_contents(app_path('client_data.json')), true);
            $newData = [
                'src_address' => $targetIp,
                'interface' => $targetInterface,
                'host_name' => $targetHostName,
            ];

            // Periksa apakah data sudah ada sebelumnya
            $existingDataKeys = array_column($existingData, 'src_address');
            if (!in_array($targetIp, $existingDataKeys)) {
                // Tidak ada, maka tambah data baru
                $existingData[] = $newData;

                // Encode menjadi JSON
                $jsonData = json_encode($existingData, JSON_PRETTY_PRINT);

                // Simpan data ke file JSON
                file_put_contents(app_path('client_data.json'), $jsonData);
            }

            // Ambil data torch hanya jika IP dan interface ditemukan
            $torchData = null;
            if ($targetInterface && $targetIp) {
                $torch = $API->comm('/tool/torch', [
                    'interface' => $targetInterface,
                    'src-address' => $targetIp,
                    'duration' => '5s'
                ]);
                $torchData = !empty($torch) ? end($torch) : null;
            }

            // Mengirim data ke view
            return view('Network.clientList', [
                'leases' => $leases,
                'address' => $address,
                'torch' => $torchData,
                'target_ip' => $targetIp,
                'target_interface' => $targetInterface,
                'interface' => $interface,
                'queueType' => $queueType,
                'simpleQueue' => $simpleQueue,
            ]);
        } else {
            return view('Network.clientList', ['error' => 'Gagal terhubung ke MikroTik']);
        }
    }


    public function fetchClientData()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Ambil daftar lease DHCP

            $clientData = [];



            $jsonData = file_get_contents(app_path('client_data.json'));

            // Decode JSON menjadi array
            $dataArray = json_decode($jsonData, true);

            // Ambil nilai langsung dari array dan simpan dalam variabel terpisah
            $srcAddress = $dataArray[0]['src_address'];
            $interface = $dataArray[1]['interface'];
            $hostName = $dataArray[2]['host_name'];

            // Ambil data torch jika ada
            $torchData = null;
            if ($interface && $srcAddress) {
                $torch = $API->comm('/tool/torch', [
                    'interface' => $interface,
                    'src-address' => $srcAddress,
                    'duration' => '5s'
                ]);
                $torchData = !empty($torch) ? end($torch) : null;
            }

            // Simpan data client
            $clientData[] = [
                'host_name' => $hostName,
                'address' => $srcAddress,
                'interface' => $interface ?? 'N/A',
                'tx' => $torchData['tx'] ?? '0',
                'rx' => $torchData['rx'] ?? '0',
            ];
        }

        return response()->json(['clients' => $clientData]);
    }
}
