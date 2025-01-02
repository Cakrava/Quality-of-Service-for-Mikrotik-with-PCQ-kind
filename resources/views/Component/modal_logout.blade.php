  <div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="customModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="customModalLabel">Konfirmasi Keluar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    Apakah Anda yakin ingin keluar?
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="location.href='{{ route('auth.logout') }}'">Ya</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                  </div>
                </div>
              </div>
            </div>
