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

            $interface = $API->comm('/interface/print');
            $address = $API->comm('/ip/address/print');

            $data = [
                'interface' => $interface,
                'address' => $address,

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

    function clientList()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Ambil daftar lease DHCP
            $dhcpLeases = $API->comm('/ip/dhcp-server/lease/print');

            $simpleQueue = $API->comm('/queue/simple/print');
            $interface = $API->comm('/interface/print');
            $address = $API->comm('/ip/address/print');
            $queueType = $API->comm('/queue/type/print');
            $simpleQueueType = $API->comm('/queue/simple/type/print');

            $data = [
                'dhcpLeases' => $dhcpLeases,
            ];

            session()->put('active_page', 'clientList');
            return view('Network.clientList', $data);
        } else {
            return redirect('failed');
        }
    }

    public function trafficUsage()
    {


        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Ambil data interface
            $interface = $API->comm('/interface/print');

            $data = [
                'interface' => $interface,
            ];

            session()->put('active_page', 'trafficUsage');
            return view('Network.trafficData', $data);
        } else {
            return redirect('failed');
        }
    }
}
