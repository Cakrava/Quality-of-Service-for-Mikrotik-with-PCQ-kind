@extends('layout.menu')
@section('content')

<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>DHCP Client</h2>
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
                                                <th>IP Address</th>
                                                <th>MAC Address</th>
                                                <th>Host Name</th>
                                                <th>Status</th>
                                                <th>Last Seen</th>
                                            </tr>
                                        </thead>
                                        <tbody id="client-table-body">
                                            @foreach($dhcpLeases as $lease)
                                            <tr>
                                                <td>{{ $lease['address'] }}</td>
                                                <td>{{ $lease['mac-address'] }}</td>
                                                <td>{{ $lease['host-name'] }}</td>
                                                <td>{{ $lease['status'] }}</td>
                                                <td>{{ $lease['last-seen'] }}</td>
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
</div>


@endsection
