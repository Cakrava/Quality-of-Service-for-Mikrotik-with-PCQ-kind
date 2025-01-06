@extends('layout.menu')
@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>IP Address Management</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="list"><a href="{{ route('network.address') }}"><i class="fa fa-refresh"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                               <!-- Dropdown untuk memilih interface -->
                                <select class="form-control" id="interfaceSelect">
                                    @foreach($interface as $interface)
                                      <option value="{{ $interface['name'] }}" data-id="{{ $interface['.id'] }}" data-name="{{ $interface['name'] }}">
                                        {{ $interface['name'] }}
                                      </option>
                                    @endforeach
                                </select>
                                <!-- Pesan status fetch data -->
                                <div id="loader-message" style="margin-top: 10px; font-style: italic; color: #555;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
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
                                      <!-- Data akan diisi di sini -->
                                    </tbody>
                                  </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let previousData = {};
let interfacesData = [];  // Variabel global untuk menyimpan data interfaces
let isFetching = false;  // Flag untuk menandai apakah sedang melakukan fetch

// Fungsi untuk mengatur pesan loader
function setLoaderMessage(message) {
    $('#loader-message').text(message);
}

// Fungsi untuk mengambil data interface dan traffic
function fetchData() {
    if (isFetching) {
        return;
    }

    const selectedInterface = $('#interfaceSelect').find(":selected").data('id'); // Ambil ID interface yang dipilih
    const selectedInterfaceName = $('#interfaceSelect').find(":selected").data('name'); // Ambil nama interface yang dipilih

    isFetching = true;
    setLoaderMessage(`Mengambil data dari interface ${selectedInterfaceName}...`);

    $.ajax({
        url: '/fetch-traffic-data', // Ganti dengan endpoint yang sesuai
        method: 'GET',
        data: { interface: selectedInterface }, // Kirim parameter interface yang dipilih
        success: function (data) {
            // Simpan data interfaces ke variabel global
            interfacesData = data.interfaces;

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

            // Tampilkan data traffic ke dalam tabel
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
            setLoaderMessage(`Data dari interface ${selectedInterfaceName} berhasil diambil.`);
            isFetching = false;
        },
        error: function (xhr, status, error) {
            if (status !== 'abort') {
                console.error('Error fetching data:', error);
                setLoaderMessage(`Gagal mengambil data. Silakan coba lagi...`);
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

// Event listener untuk perubahan pada dropdown interface
$('#interfaceSelect').on('change', function () {
    fetchData(); // Ambil data baru ketika interface dipilih
});

// Panggil fetchData pertama kali untuk mengisi data awal
fetchData();

// Polling untuk mengambil data setiap 1 detik
setInterval(fetchData, 1000);
</script>

@endsection