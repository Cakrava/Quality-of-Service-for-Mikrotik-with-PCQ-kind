@extends('layout.menu')

@section('content')
<?php $queue = ['name' => '']; ?>

<div class="right_col" role="main">
  <h2>Dashboard</h2>

  <div style="display: flex; flex-direction: row; gap: 10px;">  <!-- Gunakan flexbox untuk mengatur posisi card -->
    <!-- Card pertama (tabel interfaces) -->
    <div class="card" style="border-radius: 10px; background-color: white; padding: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 60%;">
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Type</th>
              <th>MAC Address</th>
              <th>Status</th>
              <th>TX (Transmit)</th>
              <th>RX (Receive)</th>
             
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="interface-table-body">
        @foreach($interfaces as $interface)
              <tr>
                <td>{{ $interface['name'] }}</td>
                <td>{{ $interface['type'] }}</td>
                <td>{{ $interface['mac-address'] }}</td>
                <td>{{ $interface['running'] ? 'Running' : 'Not Running' }}</td>
                <td>{{ isset($interface['tx-byte']) ? number_format($interface['tx-byte'] / 1024, 2) . ' KB/s' : 'N/A' }}</td> <!-- TX -->
                <td>{{ isset($interface['rx-byte']) ? number_format($interface['rx-byte'] / 1024, 2) . ' KB/s' : 'N/A' }}</td> <!-- RX -->
                
                <td>
                  <a title="Load Data" class="btn btn-warning">
                    <i class="fa fa-refresh" aria-hidden="true"> Load data</i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    <!-- Card kedua (misalnya, card yang menampilkan "res") -->
    <div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 40%;">
    <p><i class="fa fa-line-chart"></i> Wireless statistic</p>
    </div>
  </div>
  <div style="display: flex; flex-direction: row; gap: 10px;">
<div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px;width: 50%">
     <p><i class="fa fa-link"></i> Address</p>
    </div>
    <div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px;width: 50%">
         <p><i class="fa fa-cloud"></i> DNS</p>
        </div>
    
</div>
<div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px">
     <p><i class="fa fa-list"></i> Queue list</p>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
let previousData = {};
let interfacesData = [];  // Variabel global untuk menyimpan data interfaces

function fetchInterfaces() {
    $.ajax({
        url: '/fetch-interfaces',
        method: 'GET',
        success: function (data) {
            interfacesData = data.interfaces;  // Simpan data interfaces ke dalam variabel global
            const rows = data.interfaces.map(function (interface) {
                const currentTx = interface['tx-byte'] || 0;
                const currentRx = interface['rx-byte'] || 0;
                const previousTx = previousData[interface['name']]?.tx || 0;
                const previousRx = previousData[interface['name']]?.rx || 0;

                // Hitung kecepatan dalam byte per detik, lalu konversi ke kbps
                const txSpeedKbps = ((currentTx - previousTx) * 8 / 1000).toFixed(2); // konversi byte ke kbps
                const rxSpeedKbps = ((currentRx - previousRx) * 8 / 1000).toFixed(2); // konversi byte ke kbps

                // Simpan data saat ini untuk perbandingan di polling berikutnya
                previousData[interface['name']] = {
                    tx: currentTx,
                    rx: currentRx
                };

                return `
                    <tr>
                        <td>${interface['name']}</td>
                        <td>${interface['type']}</td>
                        <td>${interface['mac-address']}</td>
                        <td>${interface['running'] ? 'Running' : 'Not Running'}</td>
                        <td >${txSpeedKbps} kbps</td>
                        <td>${rxSpeedKbps} kbps</td>
                        <td>
                            <a title="Rename" 
                               data-toggle="modal" 
                               data-target="#configureInterface" 
                               class="btn btn-success" 
                               data-interface-name="${interface['mac-address']}">
                                <i class="fa fa-edit" aria-hidden="true"> Rename</i>
                            </a>
                        </td>
                    </tr>
                `;
            }).join(''); // Gabungkan semua row menjadi satu string

            // Update tabel hanya sekali
            $('#interface-table-body').html(rows);
        },
        error: function (xhr, status, error) {
            console.error('Error fetching interfaces:', error);
        }
    });
}

setInterval(fetchInterfaces, 1000); // Polling setiap 1 detik

// Event listener untuk menangani klik pada tombol Rename
$(document).on('click', '[data-target="#configureInterface"]', function() {
    // Ambil data-interface-name dari tombol yang diklik (MAC Address)
    const macAddress = $(this).data('interface-name');
    
    // Log untuk memastikan data yang diambil sesuai
    console.log("MAC Address yang diklik:", macAddress);

    // Mengambil data interface berdasarkan macAddress
    const selectedInterface = interfacesData.find(function(interface) {
        return interface['mac-address'] === macAddress;
    });

    if (selectedInterface) {
        // Isi input di dalam modal dengan data yang sesuai
        $('#configureInterface #interfaceName').val(selectedInterface['name']);  // Nama interface
        $('#configureInterface #macAddress').val(selectedInterface['mac-address']);  // MAC Address
        // Kamu bisa menambahkan input lain sesuai dengan kebutuhan, misalnya untuk jenis interface, status, dll
    }
});
</script>




@endsection
