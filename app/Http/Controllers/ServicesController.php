<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouterosApi;

class ServicesController extends Controller
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
    // simple queue


    public function simple_queue()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            $simpleQueue = $API->comm('/queue/simple/print');
            $interface = $API->comm('/interface/print');
            $queueType = $API->comm('/queue/type/print', ['?kind' => 'pcq']);
            $address = $API->comm('/ip/address/print');

            $data = [
                'simpleQueue' => $simpleQueue,
                'interface' => $interface,
                'queueType' => $queueType,
                'address' => $address,
            ];

            session()->put('active_page', 'simple_queue');
            return view('QualityofServices.simple_queue', $data);
        } else {
            return redirect('failed');
        }
    }

    public function deleteQueueConfiguration(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;  // Matikan debugging agar tidak ada print yang muncul
        $API->timeout = 10;   // Atur timeout untuk koneksi yang lebih lama jika diperlukan

        // Validasi input deleteID
        $request->validate([
            'deleteID' => 'required|string',  // ID yang diterima untuk menghapus queue
        ]);

        $deleteID = $request->input('deleteID');  // ID queue yang ingin dihapus

        if ($API->connect($ip, $user, $password)) {
            // Perintah untuk menghapus queue berdasarkan ID
            $API->comm('/queue/simple/remove', [
                'numbers' => $deleteID,  // ID queue yang ingin dihapus
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('qos.simple_queue')->with('success', "Queue dengan ID '$deleteID' berhasil dihapus.");
        } else {
            // Jika koneksi gagal
            return redirect()->route('qos.simple_queue')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }


    // Fungsi untuk menambahkan queue baru
    public function saveQueueConfiguration(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;  // Matikan debugging agar tidak ada print yang muncul
        $API->timeout = 10;   // Atur timeout untuk koneksi yang lebih lama jika diperlukan

        // Validasi input
        $request->validate([
            'queueName' => 'required|string',
            'networkTarget' => 'required|string',
            'maxUpload' => 'required|string',
            'maxDownload' => 'required|string',
            'typeUpload' => 'required|string',
            'typeDownload' => 'required|string',
        ]);

        // Pastikan input valid
        $queueName = $request->input('queueName');
        $networkTarget = $request->input('networkTarget');
        $maxUpload = $request->input('maxUpload');
        $maxDownload = $request->input('maxDownload');
        $typeUpload = $request->input('typeUpload');
        $typeDownload = $request->input('typeDownload');
        $queueId = $request->input('id');  // ID queue yang diterima untuk update, jika ada

        if ($API->connect($ip, $user, $password)) {
            if (empty($queueId)) {
                // Jika ID kosong, berarti akan melakukan save (tambah data baru)
                $API->comm('/queue/simple/add', [
                    'name' => $queueName,              // Nama Queue
                    'target' => $networkTarget,        // Network dengan subnet (misal: 192.168.221.0/24)
                    'max-limit' => $maxUpload . '/' . $maxDownload,  // Batas upload dan download
                    'queue' => $typeUpload . '/' . $typeDownload,    // Tipe queue upload dan download
                ]);

                // Redirect dengan pesan sukses
                return redirect()->route('qos.simple_queue')->with('success', "Queue '$queueName' berhasil ditambahkan.");
            } else {
                // Jika ID ada, berarti akan melakukan update
                $API->comm('/queue/simple/set', [
                    'numbers' => $queueId,               // ID queue yang ditemukan
                    'name' => $queueName,                 // Nama Queue
                    'target' => $networkTarget,           // Network dengan subnet (misal: 192.168.221.0/24)
                    'max-limit' => $maxUpload . '/' . $maxDownload,  // Batas upload dan download
                    'queue' => $typeUpload . '/' . $typeDownload,    // Tipe queue upload dan download
                ]);

                // Redirect dengan pesan sukses
                return redirect()->route('qos.simple_queue')->with('success', "Queue '$queueName' berhasil diperbarui.");
            }
        } else {
            // Jika koneksi gagal
            return redirect()->route('qos.simple_queue')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }




    // queue typet
    public function queue_type()
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            $simpleQueue = $API->comm('/queue/simple/print');
            $interface = $API->comm('/interface/print');
            $queueType = $API->comm('/queue/type/print', ['?kind' => 'pcq']); // Mengambil data queue type dengan kind pcq saja
            $address = $API->comm('/ip/address/print');
            $data = [
                'simpleQueue' => $simpleQueue,
                'interface' => $interface,
                'queueType' => $queueType, // Menambahkan data queue type ke array
                'address' => $address,
            ];

            session()->put('active_page', 'queue_type');
            return view('QualityofServices.queue_type', $data);
        } else {
            return redirect('failed');
        }
    }

    public function saveQueueType(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;  // Matikan debugging agar tidak ada print yang muncul
        $API->timeout = 10;   // Atur timeout untuk koneksi yang lebih lama jika diperlukan

        // Validasi input
        $request->validate([
            'queueName' => 'required|string',
            'pcqRate' => 'required|string',
            'pcqClassifier' => 'required|string',
            'pcqLimit' => 'required|string',
        ]);

        // Pastikan input valid
        $queueName = $request->input('queueName');
        $pcqRate = $request->input('pcqRate');
        $pcqClassifier = $request->input('pcqClassifier');
        $pcqLimit = $request->input('pcqLimit');
        $queueId = $request->input('hiddenId');  // ID queue yang diterima untuk update, jika ada

        // Memastikan koneksi API MikroTik
        if ($API->connect($ip, $user, $password)) {
            if (empty($queueId)) {
                // Jika ID kosong, berarti akan melakukan save (tambah data baru)
                // Untuk PCQ Queue
                $API->comm('/queue/type/add', [
                    'name' => $queueName,                // Nama Queue
                    'kind' => 'pcq',                    // Jenis queue (PCQ)
                    'pcq-rate' => $pcqRate,             // Rate untuk PCQ
                    'pcq-classifier' => $pcqClassifier, // Classifier (src-address atau dst-address)
                ]);

                // Menambahkan konfigurasi untuk simple queue dengan queue type yang baru
                $API->comm('/queue/simple/add', [
                    'name' => $queueName,                     // Nama Queue
                    'target' => $pcqClassifier,               // Classifier (src-address atau dst-address)
                    'max-limit' => $pcqRate . '/' . $pcqLimit, // Batas upload dan download
                    'queue-type' => $queueName,               // Menyambungkan ke type yang baru saja dibuat
                ]);

                // Redirect dengan pesan sukses
                return redirect()->route('qos.queue_type')->with('success', "Queue '$queueName' berhasil ditambahkan.");
            } else {
                // Jika ID ada, berarti akan melakukan update
                // Update PCQ Queue Type
                $API->comm('/queue/type/set', [
                    'numbers' => $queueId,                  // ID Queue yang akan diupdate
                    'name' => $queueName,                    // Nama Queue
                    'kind' => 'pcq',                         // Jenis queue (PCQ)
                    'pcq-rate' => $pcqRate,                  // Rate untuk PCQ
                    'pcq-classifier' => $pcqClassifier,      // Classifier (src-address atau dst-address)
                ]);

                // Update Simple Queue yang terhubung dengan PCQ
                $API->comm('/queue/simple/set', [
                    'numbers' => $queueId,                    // ID Queue yang ditemukan
                    'name' => $queueName,                     // Nama Queue
                    'target' => $pcqClassifier,               // Classifier (src-address atau dst-address)
                    'max-limit' => $pcqRate . '/' . $pcqLimit, // Batas upload dan download
                    'queue-type' => $queueName,               // Menyambungkan ke type yang baru
                ]);

                // Redirect dengan pesan sukses
                return redirect()->route('qos.queue_type')->with('success', "Queue '$queueName' berhasil diperbarui.");
            }
        } else {
            // Jika koneksi gagal
            return redirect()->route('qos.queue_type')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }

    public function delete_queue($id)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;

        if ($API->connect($ip, $user, $password)) {
            // Hapus data queue type
            $API->write('/queue/type/remove', false);
            $API->write('=.id=' . $id);
            $API->read();
            $API->disconnect();

            return redirect()->route('qos.queue_data');
        } else {
            return redirect('failed');
        }
    }
    public function deleteQueueType(Request $request)
    {
        $ip = session()->get('ip');
        $user = session()->get('user');
        $password = session()->get('password');
        $API = new RouterosApi();
        $API->debug = false;  // Matikan debugging agar tidak ada print yang muncul
        $API->timeout = 10;   // Atur timeout untuk koneksi yang lebih lama jika diperlukan

        // Validasi input deleteID
        $request->validate([
            'deleteID' => 'required|string',  // ID yang diterima untuk menghapus queue
        ]);

        $deleteID = $request->input('deleteID');  // ID queue yang ingin dihapus

        if ($API->connect($ip, $user, $password)) {
            // Perintah untuk menghapus queue berdasarkan ID
            $API->comm('/queue/type/remove', [
                'numbers' => $deleteID,  // ID queue yang ingin dihapus
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('qos.queue_type')->with('success', "Queue dengan ID '$deleteID' berhasil dihapus.");
        } else {
            // Jika koneksi gagal
            return redirect()->route('qos.queue_type')->with('error', 'Gagal terhubung ke perangkat MikroTik.');
        }
    }
}
