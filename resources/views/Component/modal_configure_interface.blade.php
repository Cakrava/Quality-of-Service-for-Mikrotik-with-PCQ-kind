<!-- Modal untuk konfigurasi interface -->
<div class="modal fade" id="configureInterface" tabindex="-1" role="dialog" aria-labelledby="configureInterfaceLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configureInterfaceLabel">Configure Interface</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk mengupdate interface name -->
                <form action="{{ route('dashboard.save_name') }}" method="POST">
                    @csrf
                    <!-- Input untuk interface name (disabled) -->
                    <div class="form-group">
                        <label for="interfaceName">Interface Name</label>
                        <input type="text" class="form-control" id="interfaceName" readonly name="interfaceName">
                    </div>
                    <!-- Input untuk MAC Address (disabled) -->
                    <div class="form-group">
                        <label for="macAddress">MAC Address</label>
                        <input type="text" class="form-control" id="macAddress" disabled name="macAddress">
                    </div>
                    <!-- Input untuk New Interface Name -->
                    <div class="form-group">
                        <label for="newInterfaceName">New Interface Name</label>
                        <input type="text" class="form-control" id="newInterfaceName" placeholder="Enter new interface name" name="newInterfaceName" required onkeyup="checkNewInterfaceName()">
                    </div>
            </div>
            <div class="modal-footer">
                <!-- Tombol Close modal -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Tombol Save changes, yang akan mengirim form -->
                <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                    Save changes</button>
                
            </div>
                </form>
        </div>
    </div>
</div>
<script>
    function checkNewInterfaceName() {
        const newInterfaceName = document.getElementById('newInterfaceName').value;
        const submitBtn = document.getElementById('submit-btn');
        if (newInterfaceName.trim() === '') {
            submitBtn.disabled = true;
            submitBtn.classList.add('bg-secondary');
            submitBtn.classList.remove('bg-success');
        } else {
            submitBtn.disabled = false;
            submitBtn.classList.add('bg-success');
            submitBtn.classList.remove('bg-secondary');
        }
    }
</script>
