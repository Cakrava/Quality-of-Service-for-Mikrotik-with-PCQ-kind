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
                        <li class="list"><a href="{{ route('network.clientList') }}"><i class="fa fa-refresh"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <a class="btn btn-success" data-toggle="modal" data-target="#configureAddress"><i class="fa fa-plus"> New IP Address</i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-box table-responsive">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Network</th>
                                                <th>Address</th>
                                                <th>Host Name</th>
                                                <th>Interface</th>
                                                <th>Upload</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>
                                        <tbody id="client-table-body">
                                            <!-- Data client akan dimasukkan di sini oleh AJAX -->
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
</div>

<script>
    let previousData = {};  // Menyimpan data sebelumnya untuk perbandingan

    // Fungsi untuk mengambil data client dari controller
    function fetchClientData() {
        $.ajax({
            url: '/fetch-client-data',
            method: 'GET',
            success: function (data) {
                const rows = data.clients.map(function (client) {
                    const currentTx = client.tx || 0;
                    const currentRx = client.rx || 0;
                    const previousTx = previousData[client.address]?.tx || 0;
                    const previousRx = previousData[client.address]?.rx || 0;

                    // Hitung kecepatan dalam byte per detik, lalu konversi ke kbps dan mbps
                    const txSpeedKbps = ((currentTx - previousTx) * 8 / 1000).toFixed(2); // konversi byte ke kbps
                    const rxSpeedKbps = ((currentRx - previousRx) * 8 / 1000).toFixed(2); // konversi byte ke kbps
                    const txSpeedMbps = (txSpeedKbps / 1000).toFixed(2); // konversi kbps ke mbps
                    const rxSpeedMbps = (rxSpeedKbps / 1000).toFixed(2); // konversi kbps ke mbps

                    // Tambahkan string kbps atau mbps dibelakangnya
                    const txSpeed = txSpeedMbps > 1 ? `${txSpeedMbps} Mbps` : `${txSpeedKbps} kbps`;
                    const rxSpeed = rxSpeedMbps > 1 ? `${rxSpeedMbps} Mbps` : `${rxSpeedKbps} kbps`;

                    // Simpan data saat ini untuk perbandingan di polling berikutnya
                    previousData[client.address] = {
                        tx: currentTx,
                        rx: currentRx
                    };

                    return `
                        <tr>
                            <td>${client.network || 'N/A'}</td>  <!-- Menampilkan Network -->
                            <td>${client.address}</td>  <!-- Menampilkan Address -->
                            <td>${client.host_name}</td>  <!-- Menampilkan Host Name -->
                            <td>${client.interface}</td>  <!-- Menampilkan Interface -->
                            <td>${txSpeed} </td>  <!-- Menampilkan TX Speed -->
                            <td>${rxSpeed} </td>  <!-- Menampilkan RX Speed -->
                        </tr>
                    `;
                }).join(''); // Gabungkan semua row menjadi satu string

                // Update tabel
                $('#client-table-body').html(rows);
            },
            error: function (xhr, status, error) {
                console.error('Error fetching client data:', error);
            }
        });
    }

    // Polling data setiap 1 detik
    setInterval(fetchClientData, 1000);
</script>

@endsection
