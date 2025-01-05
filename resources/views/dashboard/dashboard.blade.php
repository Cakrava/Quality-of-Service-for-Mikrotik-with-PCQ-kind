@extends('layout.menu')

@section('content')
<?php $queue = ['name' => '']; ?>

<div class="right_col" role="main" style="margin-bottom: :20px">
  <div class="card" style="border-radius: 10px; background-color: white; padding: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%;margin-bottom : 10px;margin-top :50px">
  <h2 style="margin-left: 20px">Dashboard</h2>
  </div>

  <div style="display: flex; flex-direction: row; gap: 10px;">  <!-- Gunakan flexbox untuk mengatur posisi card -->
    <!-- Card pertama (tabel interfaces) -->
    <div class="card" style="border-radius: 10px; background-color: white; padding: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 60%;">
      <div class="card-body" style="padding: 10px">
        <p><i class="fa fa-plug"></i> Interface</p>
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
    <canvas id="interfaceTrafficChart"></canvas>
         
    </div>
  </div>



  <div style="display: flex; flex-direction: row; gap: 10px;">
<div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px;width: 50%">
  <div style="display: flex; flex-direction: row; justify-content: space-between;">   
  <p><i class="fa fa-link"></i> Address</p>
  ><a href="{{ route('network.address') }}"><i class="fa fa-pencil-square-o"></i> Manage address</a>
</div>
     <table class="table table-striped">
      <thead>
          <tr>
              <th>Address</th>
              <th>Network</th>
              <th>Interface</th>
              <th>Dynamic</th>
             
          </tr>
      </thead>
      <tbody> 
          @foreach($address as $address)
          <tr>
              <td>{{ $address['address'] }}</td>
              <td>{{ $address['network'] }}</td>
              <td>{{ $address['interface'] }}</td>
              <td>{{ $address['dynamic'] == 'true' ? 'Yes' : 'No' }}</td>
              
          </tr>
          @endforeach
      </tbody>
  </table>
    </div>


    <div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px;width: 50%">
         <p><i class="fa fa-chain-broken"></i> Traffic monitor</p>
       
    <div class="form-group">
      <label for="interfaceSelect">Select Interface</label>
      <select class="form-control" id="interfaceSelect">
        @foreach($interfaces as $interface)
          <option value="{{ $interface['name'] }}" data-id="{{ $interface['.id'] }}" data-name="{{ $interface['name'] }}">
            {{ $interface['name'] }}
          </option>
        @endforeach
      </select>      
    </div>
    <p id="loader-message" style="font-weight: bold; color: teal; text-align: left;">Initializing...</p>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>IP</th>
          <th>Hostname</th>
          <th>MAC Address</th>
          <th>TX (Transmit)</th>
          <th>RX (Receive)</th>
        </tr>
      </thead>
  <tbody id="traffic-table-body">
        <!-- Data will be populated here -->
      </tbody>
    </table>
        </div>
    
</div>

<div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px">
     <div style="display: flex; flex-direction: row; justify-content: space-between;">
      <p><i class="fa fa-list"></i> Queue list</p>
         
      ><a href="{{ route('qos.simple_queue') }}"><i class="fa fa-pencil-square-o"></i> Manage queue</a>
         
     </div>

     <table class="table table-striped table-hover">
      <thead>
          <tr>
              <th>Queue Name</th>
              <th>Target</th>
              <th>Max Upload</th>
              <th>Max Download</th>
              <th>Queue Type</th>
              <th>Rate</th>
          </tr>
      </thead>
      <tbody>
          @foreach($simpleQueue as $queue)
          <tr>
              <td>{{ $queue['name'] }}</td>
              <td>{{ $queue['target'] }}</td>
      <td>
{{
(int)(explode('/', $queue['max-limit'])[0]) == 0 
? 'Unlimited' 
: ((int)(explode('/', $queue['max-limit'])[0]) >= 1000000 
? (int)(explode('/', $queue['max-limit'])[0]) / 1000000 . 'M' 
: (int)(explode('/', $queue['max-limit'])[0]) / 1000 . 'k')
}}
</td>
<td>
{{
(int)(explode('/', $queue['max-limit'])[1]) == 0 
? 'Unlimited' 
: ((int)(explode('/', $queue['max-limit'])[1]) >= 1000000 
? (int)(explode('/', $queue['max-limit'])[1]) / 1000000 . 'M' 
: (int)(explode('/', $queue['max-limit'])[1]) / 1000 . 'k')
}}
</td>
              <td>{{ $queue['queue'] }}</td>
              <td>{{ $queue['rate'] }}</td>
              
          </tr>
          @endforeach
      </tbody>
  </table>

    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let previousData = {};
let interfacesData = [];  // Variabel global untuk menyimpan data interfaces
let currentRequest = null;  // Variabel untuk menyimpan request saat ini
let isFetching = false;  // Flag untuk menandai apakah sedang melakukan fetch

// Data untuk bar chart
const interfaceTrafficData = {
    labels: [], // Nama-nama interface (akan diisi dari data tabel)
    datasets: [
        {
            label: 'TX (Transmit)', // Label untuk TX
            data: [], // Data TX (akan diisi dari data tabel)
            backgroundColor: 'rgba(54, 162, 235, 0.5)', // Warna untuk TX
            borderColor: 'rgba(54, 162, 235, 1)', // Warna border untuk TX
            borderWidth: 1 // Ketebalan border
        },
        {
            label: 'RX (Receive)', // Label untuk RX
            data: [], // Data RX (akan diisi dari data tabel)
            backgroundColor: 'rgba(75, 192, 192, 0.5)', // Warna untuk RX
            borderColor: 'rgba(75, 192, 192, 1)', // Warna border untuk RX
            borderWidth: 1 // Ketebalan border
        }
    ]
};

// Konfigurasi chart
const config = {
    type: 'bar', // Jenis chart (bar chart)
    data: interfaceTrafficData,
    options: {
        scales: {
            y: {
                beginAtZero: true // Mulai sumbu Y dari 0
            }
        },
        responsive: true, // Chart responsif
        plugins: {
            legend: {
                position: 'top', // Posisi legend
            },
            tooltip: {
                enabled: true // Aktifkan tooltip
            }
        }
    }
};

// Buat bar chart
const interfaceTrafficChart = new Chart(
    document.getElementById('interfaceTrafficChart'), // Elemen canvas
    config // Konfigurasi chart
);

// Fungsi untuk mengupdate data bar chart berdasarkan data tabel
function updateChartFromTable() {
    const labels = []; // Nama-nama interface
    const txData = []; // Data TX
    const rxData = []; // Data RX

    // Loop melalui setiap baris di tabel
    $('#interface-table-body tr').each(function () {
        const cells = $(this).find('td'); // Ambil semua sel di baris ini
        labels.push(cells.eq(0).text()); // Nama interface (kolom pertama)
        txData.push(parseFloat(cells.eq(4).text())); // TX (kolom kelima)
        rxData.push(parseFloat(cells.eq(5).text())); // RX (kolom keenam)
    });

    // Update data chart
    interfaceTrafficChart.data.labels = labels;
    interfaceTrafficChart.data.datasets[0].data = txData;
    interfaceTrafficChart.data.datasets[1].data = rxData;
    interfaceTrafficChart.update(); // Render ulang chart
}

// Fungsi untuk mengambil data interface dan traffic secara bersamaan
function fetchData() {
    if (isFetching) {
        return;
    }

    const selectedInterface = $('#interfaceSelect').find(":selected").data('id');
    const selectedInterfaceName = $('#interfaceSelect').find(":selected").data('name');


    if (currentRequest) {
        currentRequest.abort();
    }

    isFetching = true;
    setLoaderMessage(`Getting data from interface ${selectedInterfaceName}...`);

    currentRequest = $.ajax({
        url: '/fetch-all-data',
        method: 'GET',
        data: { interface: selectedInterface },
        success: function (data) {
            // Simpan data interfaces ke variabel global
            interfacesData = data.interfaces;  // <-- Ini yang perlu diperbaiki

            // Proses data interfaces
            const interfaceRows = data.interfaces.map(function (interface) {
                const currentTx = interface['tx-byte'] || 0;
                const currentRx = interface['rx-byte'] || 0;
                const previousTx = previousData[interface['name']]?.tx || 0;
                const previousRx = previousData[interface['name']]?.rx || 0;

                const txSpeedKbps = ((currentTx - previousTx) * 8 / 1000).toFixed(2);
                const rxSpeedKbps = ((currentRx - previousRx) * 8 / 1000).toFixed(2);

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
                        <td>${txSpeedKbps} kbps</td>
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
            }).join('');

            $('#interface-table-body').html(interfaceRows);
            updateChartFromTable();

            // Proses data traffic (jika diperlukan)
            const updatedTraffic = {};
            data.traffic.forEach((entry) => {
                const srcAddress = entry['src-address'];
                if (updatedTraffic[srcAddress]) {
                    updatedTraffic[srcAddress].tx += entry['tx-total'];
                    updatedTraffic[srcAddress].rx += entry['rx-total'];
                } else {
                    updatedTraffic[srcAddress] = {
                        srcAddress,
                        hostname: entry.hostname || 'N/A',
                        macAddress: entry['mac-address'] || 'N/A',
                        tx: entry['tx-total'],
                        rx: entry['rx-total'],
                    };
                }
            });

            const trafficRows = Object.values(updatedTraffic).map((entry) => {
                const txFormatted = formatDataSize(entry.tx);
                const rxFormatted = formatDataSize(entry.rx);
                return `
                    <tr>
                      <td>${entry.srcAddress}</td>
                      <td>${entry.hostname}</td>
                      <td>${entry.macAddress}</td>
                      <td>${txFormatted}</td>
                      <td>${rxFormatted}</td>
                    </tr>
                `;
            }).join('');

            $('#traffic-table-body').html(trafficRows);
            setLoaderMessage(`Success getting data from interface ${selectedInterfaceName}`);
            isFetching = false;
        },
        error: function (xhr, status, error) {
            if (status !== 'abort') {
                console.error('Error fetching data:', error);
                setLoaderMessage(`Failed to get data. Retrying...`);
                isFetching = false;
            }
        }
    });
}
// Fungsi untuk memformat ukuran data
function formatDataSize(value) {
    if (value < 1024) {
        return value.toFixed(2) + ' Bytes';
    } else if (value < 1048576) {
        return (value / 1024).toFixed(2) + ' KB';
    } else if (value < 1073741824) {
        return (value / 1048576).toFixed(2) + ' MB';
    } else {
        return (value / 1073741824).toFixed(2) + ' GB';
    }
}

// Fungsi untuk mengatur pesan loader
function setLoaderMessage(message) {
    $('#loader-message').text(message);
}

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

// Update data ketika interface berubah
$('#interfaceSelect').on('change', function () {
    fetchData(); // Fetch data baru ketika interface dipilih
});

// Panggil fetchData pertama kali
fetchData();

// Polling untuk mengambil data setiap 1 detik
setInterval(fetchData, 1000);
</script>@endsection
