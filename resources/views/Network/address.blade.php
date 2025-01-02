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
                                <a class="btn btn-success" data-toggle="modal" data-target="#configureAddress"><i class="fa fa-plus"> New IP Address</i></a>
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
                                            <th>Address</th>
                                            <th>Network</th>
                                            <th>Interface</th>
                                            <th>Dynamic</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        @foreach($address as $address)
                                        <tr>
                                            <td>{{ $address['address'] }}</td>
                                            <td>{{ $address['network'] }}</td>
                                            <td>{{ $address['interface'] }}</td>
                                            <td>{{ $address['dynamic'] == 'true' ? 'Yes' : 'No' }}</td>
                                            <td>
                                                <!-- Modify Button -->
                                                <a class="btn btn-warning" data-toggle="modal" 
                                                data-target="#configureAddress" 
                                                data-id="{{ $address['.id'] }}" 
                                                data-address="{{ $address['address'] }}" 
                                                data-network="{{ $address['network'] }}" 
                                                data-interface="{{ $address['interface'] }}"
                                                data-dynamic="{{ $address['dynamic'] }}">
                                                    <i class="fa fa-edit"> Modify</i>
                                                </a>

                                                <!-- Delete Button -->
                                                <a class="btn btn-danger" data-toggle="modal" 
                                                data-target="#deleteAddress" 
                                                data-id="{{ $address['.id'] }}" 
                                                data-address="{{ $address['address'] }}">
                                                    <i class="fa fa-trash"> Delete</i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
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

<!-- Delete Modal -->
<div class="modal fade" id="deleteAddress" tabindex="-1" role="dialog" aria-labelledby="deleteAddressLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAddressLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('network.delete_address') }}" method="POST" id="deleteForm">
                    @csrf
                    <h3 id="deleteName"></h3>
                    <input type="hidden" id="deleteID" name="deleteID">
                    <input type="hidden" id="deleteAddress" name="deleteAddress">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="deleteButton" onclick="document.getElementById('deleteForm').submit();">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal untuk Modify Address
    $('#configureAddress').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button yang memicu modal
        var address = button.data('address');
        var network = button.data('network');
        var interfaceVal = button.data('interface');
        var dynamic = button.data('dynamic');
        var id = button.data('id');
        var deleteAddress = button.data('deleteAddress');
        
        var modal = $(this);
        modal.find('.modal-title').text(name ? 'Configure ' + name : 'New Address Configuration');
        modal.find('#modifyIpAddress').val(address);
        modal.find('#modifyNetwork').val(network);
        modal.find('#modifyInterface').val(interfaceVal);
        modal.find('#modifyDynamic').val(dynamic);
        modal.find('#modifyId').val(id);
        modal.find('#deleteAddress').val(deleteAddress);
    });


    
    // Modal untuk Delete Address
    $('#deleteAddress').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var address = button.data('address');
        var id = button.data('id');
        
        $('#deleteName').text('Are you sure you want to delete ' + address + '?');
        $('#deleteID').val(id);
    });


    $('#modifyIpAddress').on('input', function() {
        var inputVal = $(this).val();
    
        // Menghitung jumlah titik dalam input
        var dotCount = (inputVal.match(/\./g) || []).length;
    
        // Jika lebih dari 3 titik (lebih dari 4 oktet), batalkan input terakhir
        if (dotCount > 3) {
            $(this).val(inputVal.substring(0, inputVal.length - 1));
        }
    });
    
    // Fungsi untuk mengubah IP Address menjadi Network dengan oktet ke-4 menjadi 0
    function getNetworkFromIp(ipAddress) {
        var ipParts = ipAddress.split('/');
        var ip = ipParts[0]; // Mengambil bagian IP tanpa subnet
        var ipOctets = ip.split('.');
    
        // Pastikan hanya ada 4 oktet
        if (ipOctets.length === 4) {
            // Mengubah oktet ke-4 menjadi 0
            ipOctets[3] = '0';
    
            // Mengembalikan hasilnya sebagai alamat network
            return ipOctets.join('.'); // Asumsi subnet mask /24, sesuaikan jika perlu
        }
    
        return ipAddress; // Jika tidak valid, kembalikan input asli
    }
    
    // Event listener untuk input modifyIpAddress (jika ditulis manual)
    $('#modifyIpAddress').on('input', function() {
        var ipAddress = $(this).val();
        $('#modifyNetwork').val(getNetworkFromIp(ipAddress));
    });
</script>

@endsection
