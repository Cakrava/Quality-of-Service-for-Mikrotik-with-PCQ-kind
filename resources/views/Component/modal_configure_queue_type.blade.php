<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Modal untuk konfigurasi PCQ -->
<div class="modal fade" id="configurePCQ" tabindex="-1" role="dialog" aria-labelledby="configurePCQLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="configurePCQLabel">Configure PCQ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk konfigurasi PCQ -->
                <form action="{{ route('qos.save_queue_type') }}" method="POST" id="form-save-queue-type">
                    @csrf
                    <!-- Input untuk Queue Name (disabled) -->
                    <div class="form-group">
                        <label for="queueName">Queue Name</label>
                        <input type="text" class="form-control" id="queueName" name="queueName">
                    </div>

                    <!-- Input untuk Rate (Option) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pcqRate">Rate</label>
                                <select class="form-control" id="pcqRate" name="pcqRate">
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
                                <input hidden id="hiddenRate" name="hiddenRate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pcqClassifier">Classifier</label>
                                <select class="form-control" id="pcqClassifier" name="pcqClassifier">
                                    <option value="src-address">src-address</option>
                                    <option value="dst-address">dst-address</option>
                                </select>
                                <input hidden id="hiddenClassifier" name="hiddenClassifier">
                                <input hidden id="hiddenId" name="hiddenId">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pcqLimit">Limit</label>
                        <input type="text" class="form-control" id="pcqLimit" name="pcqLimit">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <!-- Tombol Close modal -->
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Tombol Save changes, yang akan mengirim form -->
                <button type="submit" class="btn btn-success" id="submit-btn" form="form-save-queue-type">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Mengisi nilai input berdasarkan data yang diteruskan ke modal
    $('#configurePCQ').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button yang memicu modal
        var name = button.data('name');
        var id = button.data('id');
        var rate = button.data('rate');
        var limit = button.data('limit');
        var classifier = button.data('classifier');
        var modal = $(this);
        modal.find('.modal-title').text(name ? 'Edit ' + name : 'New PCQ Configuration');
        modal.find('#queueName').val(name);
        modal.find('#hiddenId').val(id);
        // Mengisi nilai rate
        if (rate) {
            modal.find('#pcqRate').val(rate); // Menyesuaikan dropdown dengan nilai rate
            modal.find('#hiddenRate').val(rate); // Menyimpan nilai rate di input hidden
        }
        if (limit) {
            modal.find('#pcqLimit').val(limit); // Menyesuaikan dropdown dengan nilai rate
            // modal.find('#hiddenLimit').val(limit); // Menyimpan nilai rate di input hidden
        }

        // Mengisi nilai classifier
        if (classifier) {
            modal.find('#pcqClassifier').val(classifier); // Menyesuaikan dropdown dengan nilai classifier
            modal.find('#hiddenClassifier').val(classifier); // Menyimpan nilai classifier di input hidden
        }
    });

    // Fungsi untuk menyesuaikan nilai input hidden dengan dropdown
    document.addEventListener('DOMContentLoaded', function() {
        // Rate dropdown
        document.getElementById('pcqRate').addEventListener('change', function() {
            document.getElementById('hiddenRate').value = this.value;
        });

        // Classifier dropdown
        document.getElementById('pcqClassifier').addEventListener('change', function() {
            document.getElementById('hiddenClassifier').value = this.value;
        });
    });

    // Memastikan tombol "Save" hanya aktif jika semua input terisi
    document.addEventListener('DOMContentLoaded', function() {
        const formFields = document.querySelectorAll('select');
        formFields.forEach(field => {
            field.addEventListener('change', function() {
                const isFormValid = Array.from(formFields).every(field => field.value.trim() !== '');
                document.getElementById('submit-btn').disabled = !isFormValid;
            });
        });
    });
</script>

<style>
    body.modal-open {
        overflow: hidden;
    }
</style>
