    @extends('layout.menu')
    @section('content')

    <div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Simple Queue Management</h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="list"><a href="{{ route('qos.simple_queue') }}"><i class="fa fa-refresh"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <a class="btn btn-success" data-toggle="modal" 
                                data-target="#configureSimpleQueue" ><i class="fa fa-plus"> New Simple Queue</i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="x_content">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
            <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>Queue Name</th>
                                                <th>Target</th>
                                                <th>Max Upload</th>
                                                <th>Max Download</th>
                                                <th>Queue Type</th>
                                                <th>Rate</th>
                                                <th>Actions</th>
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
                                                <td>
                                                    <a class="btn btn-warning" data-toggle="modal" 
                                                    data-target="#configureSimpleQueue" 
                                                    data-id="{{ $queue['.id'] }}"
                                                    data-name="{{ $queue['name'] }}"
                                                    data-target-network="{{ $queue['target'] }}"
                                                    data-max-upload="{{ (int)(explode('/', $queue['max-limit'])[0]) >= 1000000 ? (int)(explode('/', $queue['max-limit'])[0]) / 1000000 . 'M' : (int)(explode('/', $queue['max-limit'])[0]) / 1000 . 'K' }}"
                                                    data-max-download="{{ (int)(explode('/', $queue['max-limit'])[1]) >= 1000000 ? (int)(explode('/', $queue['max-limit'])[1]) / 1000000 . 'M' : (int)(explode('/', $queue['max-limit'])[1]) / 1000 . 'K' }}"
                                                    data-type-upload="{{ explode('/', $queue['queue'])[0] }}"
                                                    data-type-download="{{ explode('/', $queue['queue'])[1] }}"
                                                ><i class="fa fa-edit"> Modify</i></a>
                                                
                                                <a class="btn btn-danger" data-toggle="modal" 
                                data-target="#deleteModal" data-name="{{ $queue['name'] }}" data-id="{{ $queue['.id'] }}" ><i class="fa fa-trash"> Delete Queue</i></a>
                                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Hapus</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{ route('qos.delete_simple_queue') }}" method="POST" id="deleteForm">
                                                    @csrf
                                                    <h3 id="deleteName"></h3>
                                                    <input type="hidden" id="deleteID" name="deleteID">
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                <button type="button" class="btn btn-danger" id="deleteButton" onclick="document.getElementById('deleteForm').submit();">Ya</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                

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


    <script>
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var name = button.data('name');
            var id = button.data('id');
            $('#deleteName').text('Are you sure want to delete ' + name + '?');
            $('#deleteID').val(id);
        });
    </script>



    @endsection