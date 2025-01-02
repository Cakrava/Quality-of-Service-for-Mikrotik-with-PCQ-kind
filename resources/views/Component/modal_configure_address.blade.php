
<!-- Modify Modal -->
<div class="modal fade" id="configureAddress" tabindex="-1" role="dialog" aria-labelledby="configureAddressLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configureAddressLabel">Modify Address</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('network.save_address') }}" method="POST" id="modifyForm">
                    @csrf
                    <!-- Input untuk Address -->
                    <div class="form-group">
                        <label for="modifyIpAddress">IP Address</label>
                        <input type="text" class="form-control" id="modifyIpAddress" name="ipAddress" placeholder="e.g., 192.168.1.1/24" required>
                    </div>

                    <!-- Input untuk Network -->
                    <div class="form-group">
                        <label for="modifyNetwork">Network</label>
                        <input type="text" class="form-control" id="modifyNetwork" name="network" placeholder="e.g., 192.168.1.0" required readonly>
                    </div>

                    <!-- Input untuk Interface -->
                    <div class="form-group">
                        <label for="modifyInterface">Interface</label>
                        <select class="form-control" id="modifyInterface" name="interface" required>
                            <option value="">Pilih Interface</option>
                            @foreach($interface as $int)
                            @if(is_object($int) && isset($int->name))
                                <option value="{{ $int->name }}">{{ $int->name }}</option>
                            @elseif(is_array($int) && isset($int['name']))
                                <option value="{{ $int['name'] }}">{{ $int['name'] }}</option>
                            @else
                                <option value="" disabled>Data tidak valid</option>
                            @endif
                        @endforeach
                        
                        </select>
                    </div>

                    <!-- Dynamic Field -->
                   

                    <input type="hidden" id="modifyId" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="modifyButton" form="modifyForm">Save Changes</button>
            </div>
        </div>
    </div>
</div>

