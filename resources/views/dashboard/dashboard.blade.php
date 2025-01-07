@extends('layout.menu')

@section('content')
<?php $queue = ['name' => '']; ?>

<style>
    /* Container untuk progress bar */
.progress-container {
    position: relative;
    width: 120px;
    height: 120px;
    margin : 20px;
    text-align: center;
}
.progress-parent {
    width: 100%; /* Lebar diubah menjadi 100%, tapi dinamis jika ada komponen di parent yang sama */
    flex: 1; /* Membuat lebar menjadi dinamis dan adil jika ada komponen lain di parent yang sama */
    height: auto; /* Tinggi diubah menjadi 230px */
    box-shadow: 0 0px 4px rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    background-color: #fff;
    display: flex;
    flex-direction: row;
    align-items: center;
    flex-wrap: wrap; 
padding: 0 20px 20px 20px;
margin-bottom: 10px;



}
/* Lingkaran progress bar */
.progress-circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(#2E5077 0%, #ddd 0%);
    display: flex;
    align-items: center;
    justify-content: center;
    /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); */
}

/* Lingkaran dalam (untuk efek berlubang di tengah) */
.progress-circle::after {
    content: '';
    position: absolute;
    width: 80%;
    height: 80%;
    border-radius: 50%;
    background: white;
    /* box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2); */
}

/* Teks persentase */
.progress-text {
    position: absolute;
    font-size: 18px;
    font-weight: bold;
    color: #2E5077;
    z-index: 1;
}

/* Label di bawah progress bar */
.progress-label {
    margin-top: 10px;
    font-size: 14px;
    color: #333;
}

/* Warna khusus untuk setiap progress bar */
#memoryProgress {
    background: conic-gradient(#2E5077 0%, #ddd 0%);
}
#cpuProgress {
    background: conic-gradient(#a9764d 0%, #ddd 0%);
}
#uptimeProgress {
    background: conic-gradient(#79D7BE 0%, #ddd 0%);
}
</style>
<div class="right_col" role="main" style="margin-bottom: :20px">
    <div style="width: 100%; display: flex; flex-direction: row; justify-content : space-between; gap: 10px;">
        <div class="progress-parent" style="background-color: white">
            <div class="progress-container">
                <div class="progress-circle" id="memoryProgress">
                    <div class="progress-text" id="memoryText">0%</div>
                </div>
                <div class="progress-label">Memory Usage</div>
            </div>
            <!-- Detail Memory -->
            <div class="detail-container">
                <div class="detail-item">
                    <span class="detail-label">Total:</span>
                    <span class="detail-value" id="totalMemory">0 MB</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Free:</span>
                    <span class="detail-value" id="freeMemory">0 MB</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Usage:</span>
                    <span class="detail-value" id="usedMemory">0 MB</span>
                </div>
            </div>
        </div>
        
        <div class="progress-parent"  style="background-color: white">
            <div class="progress-container">
                <div class="progress-circle" id="cpuProgress">
                    <div class="progress-text" id="cpuText">0%</div>
                </div>
                <div class="progress-label">CPU Usage</div>
            </div>
            <!-- Detail CPU -->
            <div class="detail-container">
                <div class="detail-item">
                    <span class="detail-label">Frequency:</span>
                    <span class="detail-value" id="cpuFrequency">0 MHz</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Count:</span>
                    <span class="detail-value" id="cpuCount">0</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">CPU:</span>
                    <span class="detail-value" id="cpuName">N/A</span>
                </div>
            </div>
        </div>
        
        <div class="progress-parent"  style="background-color: white">
            <div class="progress-container">
                <div class="progress-circle" id="uptimeProgress">
                    <div class="progress-text" id="uptimeText">0%</div>
                </div>
                <div class="progress-label">Uptime (24h)</div>
            </div>
            <!-- Detail Uptime -->
            <div class="detail-container">
                <div class="detail-item">
                    <span class="detail-label">Base Percentage:</span>
                    <span class="detail-value" id="uptimePercentage">0%</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Uptime:</span>
                    <span class="detail-value" id="uptimeFormatted">00:00:00</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Build Time:</span>
                    <span class="detail-value" id="buildTime">N/A</span>
                </div>
            </div>
        </div>
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
    <p><i class="fa fa-bar-chart-o"></i> Interface traffic</p>
    <canvas id="interfaceTrafficChart"></canvas>
         
    </div>
  </div>



  <div style="display: flex; flex-direction: row; gap: 10px;">
<div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px;width: 50%">
  <div style="display: flex; flex-direction: row; justify-content: space-between;">   
  <p><i class="fa fa-link"></i> Address</p>
  <a href="{{ route('network.address') }}"><i class="fa fa-pencil-square-o"></i> Manage address</a>
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
        <div style="display: flex; flex-direction: row; justify-content: space-between;">
            <p><i class="fa fa-line-chart"></i> Traffic usage</p>
               
            <a href="{{ route('network.trafficUsage') }}"><i class="fa fa-pencil-square-o"></i> See more</a>
               
           </div>
      
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
    <div id="loader-message" style="margin-top: 10px; font-style: italic; color: #555;">Initialize..</div>
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

<div class="card" style="border-radius: 10px; background-color: white; padding: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-top: 10px;margin-bottom : 50px">
     <div style="display: flex; flex-direction: row; justify-content: space-between;">
      <p><i class="fa fa-list"></i> Queue list</p>
         
      <a href="{{ route('qos.simple_queue') }}"><i class="fa fa-pencil-square-o"></i> Manage queue</a>
         
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
const config = {
    type: 'bar', // Jenis chart (bar chart)
    data: interfaceTrafficData,
    options: {
        scales: {
            y: {
                beginAtZero: true, // Mulai sumbu Y dari 0
                ticks: {
                    // Gunakan callback untuk menampilkan nilai yang sudah diformat
                    callback: function (value) {
                        return formatSpeed(value); // Format nilai ke satuan yang sesuai
                    }
                }
            }
        },
        responsive: true, // Chart responsif
        plugins: {
            legend: {
                position: 'top', // Posisi legend
            },
            tooltip: {
                enabled: true, // Aktifkan tooltip
                callbacks: {
                    // Gunakan callback untuk menampilkan nilai yang sudah diformat di tooltip
                    label: function (context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += formatSpeed(context.raw); // Format nilai ke satuan yang sesuai
                        return label;
                    }
                }
            }
        }
    }
};

function updateChartFromTable() {
    const labels = []; // Nama-nama interface
    const txData = []; // Data TX (dalam bits)
    const rxData = []; // Data RX (dalam bits)

    // Loop melalui setiap baris di tabel
    $('#interface-table-body tr').each(function () {
        const cells = $(this).find('td'); // Ambil semua sel di baris ini
        labels.push(cells.eq(0).text()); // Nama interface (kolom pertama)

        // Ambil nilai numerik (txDiff dan rxDiff) dari tabel
        const txSpeedText = cells.eq(4).text(); // TX (contoh: "100.00 kbps")
        const rxSpeedText = cells.eq(5).text(); // RX (contoh: "1.50 Mbps")

        // Konversi teks yang sudah diformat kembali ke nilai numerik (dalam bits)
        const txBits = parseFormattedSpeed(txSpeedText); // Konversi ke bits
        const rxBits = parseFormattedSpeed(rxSpeedText); // Konversi ke bits

        // Simpan nilai numerik (dalam bits) untuk plotting di chart
        txData.push(txBits);
        rxData.push(rxBits);
    });

    // Update data chart
    interfaceTrafficChart.data.labels = labels;
    interfaceTrafficChart.data.datasets[0].data = txData; // Data TX (dalam bits)
    interfaceTrafficChart.data.datasets[1].data = rxData; // Data RX (dalam bits)
    interfaceTrafficChart.update(); // Render ulang chart
}

// Fungsi untuk mengonversi teks yang sudah diformat kembali ke nilai numerik (dalam bits)
function parseFormattedSpeed(speedText) {
    const value = parseFloat(speedText); // Ambil nilai numeriknya
    if (speedText.includes('kbps')) {
        return value * 1000; // Konversi kbps ke bits
    } else if (speedText.includes('Mbps')) {
        return value * 1000000; // Konversi Mbps ke bits
    } else if (speedText.includes('Gbps')) {
        return value * 1000000000; // Konversi Gbps ke bits
    } else {
        return value; // Nilai sudah dalam bps
    }
}
// Buat bar chart
const interfaceTrafficChart = new Chart(
    document.getElementById('interfaceTrafficChart'), // Elemen canvas
    config // Konfigurasi chart
);


// Fungsi untuk mengupdate circular progress bar
function updateProgressBar(elementId, textId, progress, color) {
    const progressCircle = document.getElementById(elementId);
    const progressText = document.getElementById(textId);

    // Update progress bar
    progressCircle.style.background = `conic-gradient(${color} ${progress}%, #ddd ${progress}%)`;

    // Update teks persentase
    progressText.textContent = `${progress}%`;
}

// Fungsi untuk mengambil data system stats
function fetchSystemStats() {
    $.ajax({
        url: '/fetch-system-stats', // Sesuaikan dengan endpoint yang benar
        method: 'GET',
        success: function (data) {
            // Update circular progress bar
            updateProgressBar('memoryProgress', 'memoryText', data.memoryUsage, '#2E5077');
            updateProgressBar('cpuProgress', 'cpuText', data.cpuUsage, '#4DA1A9');
            updateProgressBar('uptimeProgress', 'uptimeText', data.uptimePercentage, '#79D7BE');

            // Update detail Memory
            $('#totalMemory').text((data.totalMemory / (1024 * 1024)).toFixed(2) + ' MB');
$('#freeMemory').text((data.freeMemory / (1024 * 1024)).toFixed(2) + ' MB');
$('#usedMemory').text((data.usedMemory / (1024 * 1024)).toFixed(2) + ' MB');

            // Update detail CPU
            $('#cpuFrequency').text(data.cpuFrequency + ' MHz');
            $('#cpuCount').text(data.cpuCount);
            $('#cpuName').text(data.cpuName);

            // Update detail Uptime
            $('#uptimePercentage').text(data.uptimePercentage + '%');
            $('#uptimeFormatted').text(data.uptimeFormatted);
            $('#buildTime').text(data.buildTime || 'N/A');
            fetchSystemStats();
        },
        error: function (xhr, status, error) {
            console.error('Error fetching system stats:', error);
            fetchSystemStats();
        }
    });
}

fetchSystemStats();

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


let isFetchingData = false;
let currentRequestData = null;

// Variabel untuk fetchDataTraffic
let isFetchingTraffic = false;
let currentRequestTraffic = null;

function fetchDataTraffic() {
    if (isFetchingTraffic) {
        return;
    }

    const selectedInterface = $('#interfaceSelect').find(":selected").data('id');
    const selectedInterfaceName = $('#interfaceSelect').find(":selected").data('name');

    if (currentRequestTraffic) {
        currentRequestTraffic.abort();
    }

    isFetchingTraffic = true;
    setLoaderMessage(`Getting traffic data from interface ${selectedInterfaceName}...`);

    currentRequestTraffic = $.ajax({
        url: '/fetch-traffic-data', // Endpoint untuk mengambil data traffic
        method: 'GET',
        data: { interface: selectedInterface }, // Opsional: Jika ingin memfilter berdasarkan interface tertentu
        success: function (data) {
            // Proses data traffic
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

            // Buat baris tabel untuk traffic
            const trafficRows = Object.values(updatedTraffic).map((entry) => {
                const txFormatted = formatDataSize(entry.tx); // Benar untuk kecepatan jaringan
                const rxFormatted = formatDataSize(entry.rx); // Benar untuk kecepatan jaringan
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

            // Update tabel traffic
            $('#traffic-table-body').html(trafficRows);

            setLoaderMessage(`Success getting traffic data from interface ${selectedInterfaceName}`);
            isFetchingTraffic = false;
            setTimeout(fetchDataTraffic, 2000); // Polling setiap 2 detik
        },
        error: function (xhr, status, error) {
            if (status !== 'abort') {
                console.error('Error fetching traffic data:', error);
                setLoaderMessage(`Failed to get traffic data. Retrying...`);
                isFetchingTraffic = false;
                setTimeout(fetchDataTraffic, 2000); // Retry setiap 2 detik
            }
        }
    });
}

function formatSpeed(value) {
    const valueInBits = value * 8; // Konversi byte ke bits
    if (valueInBits < 1000) {
        return valueInBits.toFixed(2) + ' bps'; // bits per second
    } else if (valueInBits < 1000000) {
        return (valueInBits / 1000).toFixed(2) + ' kbps'; // kilobits per second
    } else if (valueInBits < 1000000000) {
        return (valueInBits / 1000000).toFixed(2) + ' Mbps'; // megabits per second
    } else {
        return (valueInBits / 1000000000).toFixed(2) + ' Gbps'; // gigabits per second
    }
}
function fetchData() {
    if (isFetchingData) {
        return;
    }

    if (currentRequestData) {
        currentRequestData.abort();
    }

    isFetchingData = true;

    currentRequestData = $.ajax({
        url: '/fetch-all-data', // Endpoint untuk mengambil data interfaces
        method: 'GET',
        success: function (data) {
            // Simpan data interfaces ke variabel global
            interfacesData = data.interfaces;

            const interfaceRows = data.interfaces.map(function (interface) {
    const currentTx = interface['tx-byte'] || 0; // Data TX (bytes)
    const currentRx = interface['rx-byte'] || 0; // Data RX (bytes)
    const previousTx = previousData[interface['name']]?.tx || 0; // Data TX sebelumnya
    const previousRx = previousData[interface['name']]?.rx || 0; // Data RX sebelumnya

    // Hitung selisih TX dan RX (dalam bytes)
    const txDiff = currentTx - previousTx;
    const rxDiff = currentRx - previousRx;

    // Konversi ke satuan yang sesuai (kbps, Mbps, Gbps)
    const txSpeed = formatSpeed(txDiff); // Contoh: "100.00 kbps"
    const rxSpeed = formatSpeed(rxDiff); // Contoh: "1.50 Mbps"

    // Simpan data TX dan RX saat ini untuk perhitungan selanjutnya
    previousData[interface['name']] = {
        tx: currentTx,
        rx: currentRx
    };

                // Buat baris tabel untuk setiap interface
                return `
                    <tr>
                        <td>${interface['name']}</td>
                        <td>${interface['type']}</td>
                        <td>${interface['mac-address']}</td>
                        <td>${interface['running'] ? 'Running' : 'Not Running'}</td>
                        <td>${txSpeed}</td>
                        <td>${rxSpeed}</td>
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

            // Update tabel interfaces
            $('#interface-table-body').html(interfaceRows);

            updateChartFromTable();

            isFetchingData = false;
            setTimeout(fetchData, 1000); // Polling setiap 1 detik
        },
        error: function (xhr, status, error) {
            if (status !== 'abort') {
                console.error('Error fetching data:', error);
                isFetchingData = false;
                setTimeout(fetchData, 1000); // Retry setiap 1 detik
            }
        }
    });
}

// Jalankan kedua fungsi
fetchData();
fetchDataTraffic();

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
    fetchDataTraffic(); // Fetch data baru ketika interface dipilih
});

// Panggil fetchData pertama kali


// Polling untuk mengambil data setiap 1 detik
// setInterval(fetchData, 1000);
</script>@endsection
