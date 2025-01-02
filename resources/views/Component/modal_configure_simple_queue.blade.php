<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Modal untuk konfigurasi interface -->
<div class="modal fade" id="configureSimpleQueue" tabindex="-1" role="dialog" aria-labelledby="configureSimpleQueueLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configureSimpleQueueLabel">Configure Simple Queue</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk mengupdate interface name -->
                <form action="{{ route('qos.save_simple_queue') }}" method="POST">
                    @csrf
                    <!-- Input untuk interface name (disabled) -->
                    <div class="form-group">
                        <label for="queueName">Queue Name</label>
                        <input type="text" class="form-control" id="queueName"  name="queueName">
                    </div>
                    <!-- Input untuk MAC Address (disabled) -->
                    <div class="form-group">
                        <label for="networkTarget">Target</label>
         <!-- Input Field Manual -->
         {{-- <input  name="networkTarget" id="networkTarget" placeholder="target"> --}}
         <input type="text" class="form-control" id="networkTarget" name="networkTarget" placeholder="192.168.1.0/24">


<!-- Select Dropdown -->
<select class="form-control" id="networkSelect" name="networkSelect">
    <option value="none">None</option> <!-- Opsi None jika tidak ada pilihan yang cocok -->
    @php
        $hasValidOptions = false; // Variabel untuk mengecek apakah ada opsi yang valid
    @endphp
    @foreach($address as $int)
        @if(is_array($int) && isset($int['interface']) && isset($int['address']))
            @php
                $addressParts = explode('/', $int['address']);
                $ip = $addressParts[0];
                $subnet = isset($addressParts[1]) ? $addressParts[1] : '24'; // Default subnet jika tidak ada

                $ipParts = explode('.', $ip);
                $network = $ipParts[0] . '.' . $ipParts[1] . '.' . $ipParts[2] . '.0'; // Menentukan network address
                $networkWithSubnet = $network . '/' . $subnet;
            @endphp
            <option value="{{ $networkWithSubnet }}">{{ $int['interface'] }}</option>
            @php
                $hasValidOptions = true; // Set menjadi true jika ada opsi yang valid
            @endphp
        @else
            <option value="" disabled>Data tidak valid</option>
        @endif
    @endforeach

    @if(!$hasValidOptions)
        <option value="none" selected>None</option> <!-- Jika tidak ada opsi yang valid, None akan dipilih secara default -->
    @endif
</select>



                    </div>
                    


                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="optionMaxUpload">Max Upload</label>
                                <select class="form-control" id="optionMaxUpload" name="optionMaxUpload">
                                    <option value="">Pilih batas Upload</option>
                                    <option value="100K">100K</option>
                                    <option value="128K">128K</option>
                                    <option value="512K">512K</option>
                                    <option value="1M">1M</option>
                                    <option value="2M">2M</option>
                                    <option value="3M">3M</option>
                                    <option value="4M">4M</option>
                                    <option value="5M">5M</option>
                                    <option value="6M">6M</option>
                                    <option value="7M">7M</option>
                                    <option value="8M">8M</option>
                                    <option value="9M">9M</option>
                                    <option value="10M">10M</option>
                                    <option value="20M">20M</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="optionMaxDownload">Max Download</label>
                                <select class="form-control" id="optionMaxDownload" name="optionMaxDownload">
                                    <option value="">Pilih batas download</option>
                                    <option value="100K">100K</option>
                                    <option value="128K">128K</option>
                                    <option value="512K">512K</option>
                                    <option value="1M">1M</option>
                                    <option value="2M">2M</option>
                                    <option value="3M">3M</option>
                                    <option value="4M">4M</option>
                                    <option value="5M">5M</option>
                                    <option value="6M">6M</option>
                                    <option value="7M">7M</option>
                                    <option value="8M">8M</option>
                                    <option value="9M">9M</option>
                                    <option value="10M">10M</option>
                                    <option value="20M">20M</option>
                                </select>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="optionTypeUpload">Queue Upload Type</label>
                                <select class="form-control" id="optionTypeUpload" name="optionTypeUpload">
                                    <option value="">Pilih Type</option>
    @foreach($queueType as $type)
        @if(is_array($type) && isset($type['name']))
            <option value="{{ $type['name'] }}">{{ $type['name'] }}</option>
        @else
            <option value="" disabled>Data tidak valid</option>
        @endif
    @endforeach
                                   
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="optionTypeDownload">Queue Download Type</label>
                                <select class="form-control" id="optionTypeDownload" name="optionTypeDownload">
                                    <option value="">Pilih Type</option>
                                    @foreach($queueType as $type)
                                        @if(is_array($type) && isset($type['name']))
                                            <option value="{{ $type['name'] }}">{{ $type['name'] }}</option>
                                        @else
                                            <option value="" disabled>Data tidak valid</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                 
            </div>

            <input type="hidden" name="id" id="id">
                
                <input type="hidden" name="maxUpload" id="maxUpload" placeholder="maxupload">
                
                <input type="hidden" name="maxDownload" id="maxDownload" placeholder="maxdownload">
                
                <input type="hidden" name="typeUpload" id="typeUpload" placeholder="uploadtype">
                
                <input type="hidden" name="typeDownload" id="typeDownload" placeholder="downloadtype">
                
              
              
          
            <div class="modal-footer">
                <!-- Tombol Close modal -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Tombol Save changes, yang akan mengirim form -->
                <button type="submit" class="btn btn-success" id="submit-btn" >
                    Save</button>
                
            </div>
            
                </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formFields = document.querySelectorAll('input[type="text"], select');
        formFields.forEach(field => {
            field.addEventListener('change', function() {
                const isFormValid = Array.from(formFields).every(field => field.value.trim() !== '');
                document.getElementById('submit-btn').disabled = !isFormValid;
            });
        });
    });
   
</script>

<script>
    // Fungsi untuk memeriksa input dan menyesuaikan dropdown
    function updateSelectOptions() {
        var networkTarget = document.getElementById('networkTarget').value;
        var networkSelect = document.getElementById('networkSelect');
        var isValid = false;

        // Loop untuk memeriksa apakah input cocok dengan salah satu option
        for (var i = 0; i < networkSelect.options.length; i++) {
            var option = networkSelect.options[i];
            if (option.value !== "none" && option.value === networkTarget) {
                isValid = true;
                networkSelect.value = option.value; // Set dropdown ke option yang cocok
                break;
            }
        }

        // Jika input tidak cocok dengan option, tampilkan "None"
        if (!networkTarget || !isValid) {
            networkSelect.value = 'none'; // Set dropdown ke "None" jika input kosong atau tidak valid
        }
    }

    // Memperbarui dropdown ketika pengguna mengetik di input field
    document.getElementById('networkTarget').addEventListener('input', updateSelectOptions);

    // Mengisi input dengan nilai dari dropdown ketika dipilih
    document.getElementById('networkSelect').addEventListener('change', function() {
        var networkTarget = document.getElementById('networkTarget');
        var selectedOption = this.options[this.selectedIndex].value;
        if (selectedOption !== 'none') {
            networkTarget.value = selectedOption; // Set input dengan value network/subnet
        } else {
            networkTarget.value = ''; // Reset input jika memilih None
        }
    });

    document.getElementById('networkTarget').addEventListener('input', function(event) {
        var value = event.target.value;
        
        // Hanya izinkan angka, titik dan garis miring
        var filteredValue = value.replace(/[^0-9\/\.]/g, '');

        // Jika ada perubahan (karakter yang tidak diinginkan dihapus), set nilai input ke filteredValue
        if (value !== filteredValue) {
            event.target.value = filteredValue;
        }
    });
</script>

<script>
function updateOptionFields() {
    var optionMaxUpload = document.getElementById('optionMaxUpload');
    var maxUploadInput = document.getElementById('maxUpload');
    var optionMaxDownload = document.getElementById('optionMaxDownload');
    var maxDownloadInput = document.getElementById('maxDownload');
    var optionTypeUpload = document.getElementById('optionTypeUpload');
    var typeUploadInput = document.getElementById('typeUpload');
    var optionTypeDownload = document.getElementById('optionTypeDownload');
    var typeDownloadInput = document.getElementById('typeDownload');

    // Fungsi untuk mengupdate input saat option berubah
    function updateInput(selectElement, inputElement) {
        selectElement.addEventListener('change', function() {
            var selectedValue = this.options[this.selectedIndex].value;
            if (selectedValue !== '') {
                inputElement.value = selectedValue; // Set input dengan value dari option yang dipilih
            } else {
                inputElement.value = ''; // Reset input jika tidak ada value yang dipilih
            }
        });
    }

    // Panggil fungsi updateInput untuk setiap pasangan select dan input
    updateInput(optionMaxUpload, maxUploadInput);
    updateInput(optionMaxDownload, maxDownloadInput);
    updateInput(optionTypeUpload, typeUploadInput);
    updateInput(optionTypeDownload, typeDownloadInput);
}

// Panggil fungsi saat DOM selesai dimuat
document.addEventListener('DOMContentLoaded', updateOptionFields);

</script>



<script>
    $('#configureSimpleQueue').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var id = button.data('id')
        var name = button.data('name')
        var target = button.data('target-network')
        var maxUpload = button.data('max-upload')
        var maxDownload = button.data('max-download')
        var typeUpload = button.data('type-upload')
        var typeDownload = button.data('type-download')
        var modal = $(this)
        modal.find('.modal-title').text(name ? 'Edit ' + name : 'New Simple Queue Configuration');
        modal.find('#id').val(id)
        modal.find('#queueName').val(name)
        modal.find('#maxUpload').val(maxUpload)
        modal.find('#maxDownload').val(maxDownload)
        modal.find('#typeUpload').val(typeUpload)
        modal.find('#typeDownload').val(typeDownload)
        modal.find('#networkTarget').val(target)
    });


    
    </script>
<script>
    $('#configureSimpleQueue').on('show.bs.modal', function() {
        // Menyesuaikan dengan maxUpload dan optionMaxUpload
        var maxUploadVal = document.getElementById('maxUpload').value.trim();
        var selectMaxUpload = document.getElementById('optionMaxUpload');
        var matchedMaxUploadOption = Array.from(selectMaxUpload.options).find(option => option.value.trim() === maxUploadVal);

        if (matchedMaxUploadOption) {
            selectMaxUpload.value = matchedMaxUploadOption.value;
        } else {
            selectMaxUpload.value = ''; // Reset jika tidak ada yang cocok
        }

        // Menyesuaikan dengan targetNetwork dan networkSelect
        var targetNetworkVal = document.getElementById('networkTarget').value.trim();
        var selectNetwork = document.getElementById('networkSelect');
        var matchedNetworkOption = Array.from(selectNetwork.options).find(option => option.value.trim() === targetNetworkVal);

        if (matchedNetworkOption) {
            selectNetwork.value = matchedNetworkOption.value;
        } else {
            selectNetwork.value = ''; // Reset jika tidak ada yang cocok
        }

        // Menyesuaikan dengan maxDownload dan optionMaxDownload
        var maxDownloadVal = document.getElementById('maxDownload').value.trim();
        var selectMaxDownload = document.getElementById('optionMaxDownload');
        var matchedMaxDownloadOption = Array.from(selectMaxDownload.options).find(option => option.value.trim() === maxDownloadVal);

        if (matchedMaxDownloadOption) {
            selectMaxDownload.value = matchedMaxDownloadOption.value;
        } else {
            selectMaxDownload.value = ''; // Reset jika tidak ada yang cocok
        }

        // Menyesuaikan dengan typeUpload dan optionTypeUpload
        var typeUploadVal = document.getElementById('typeUpload').value.trim();
        var selectTypeUpload = document.getElementById('optionTypeUpload');
        var matchedTypeUploadOption = Array.from(selectTypeUpload.options).find(option => option.value.trim() === typeUploadVal);

        if (matchedTypeUploadOption) {
            selectTypeUpload.value = matchedTypeUploadOption.value;
        } else {
            selectTypeUpload.value = ''; // Reset jika tidak ada yang cocok
        }

        // Menyesuaikan dengan typeDownload dan optionTypeDownload
        var typeDownloadVal = document.getElementById('typeDownload').value.trim();
        var selectTypeDownload = document.getElementById('optionTypeDownload');
        var matchedTypeDownloadOption = Array.from(selectTypeDownload.options).find(option => option.value.trim() === typeDownloadVal);

        if (matchedTypeDownloadOption) {
            selectTypeDownload.value = matchedTypeDownloadOption.value;
        } else {
            selectTypeDownload.value = ''; // Reset jika tidak ada yang cocok
        }
    });
</script>

<style>
    body.modal-open {
    overflow: hidden;
}

</style>