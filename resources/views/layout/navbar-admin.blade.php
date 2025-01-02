@extends('layout.main')


@section('navbar')
   <div class="top_nav">
        
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>

              <ul class="nav navbar-nav navbar-right">
                <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    @if(Auth::user())
                        <img src="{{ asset(Auth::user()->foto) }}" alt="">
                    @endif
                   @if(Auth::user())
                            <span>{{ Auth::user()->name }}</span>
                        
                          @endif
                    <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                    <li><a href="javascript:;"> Profile</a></li>
                    <li>
                      <a href="javascript:;">
                        <span class="badge bg-red pull-right">50%</span>
                        <span>Settings</span>
                      </a>
                    </li>
                    <li><a href="javascript:;">Help</a></li>
                    <li><a href="login.html"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
                  </ul>
                </li>
                <script>
  // Ambil nilai lastNotificationCount dari localStorage atau inisialisasi dengan 0 jika belum ada
  let lastNotificationCount = localStorage.getItem('lastNotificationCount') 
                              ? parseInt(localStorage.getItem('lastNotificationCount')) 
                              : 0;

  function playAudio() {
    const audio = new Audio('{{ asset("asset/sound/notifikasi.mp3") }}');
    audio.play().catch(error => console.error('Error playing audio:', error));
  }

  function loadNotifications() {
    fetch('{{ route("notifications") }}')
      .then(response => response.json())
      .then(data => {
        const notifikasi = data.notifikasi.slice(-5).reverse();
        const jumlah = data.jumlah;
        const badge = document.querySelector('.badge.bg-green');
        
        if (jumlah === 0) {
          badge.style.display = 'none';
          document.getElementById('menu1').innerHTML = '<li><a>Tidak ada pembaruan</a></li>';
        } else {
          badge.textContent = jumlah;
          badge.style.display = 'inline';
          const menuList = document.getElementById('menu1');
          menuList.innerHTML = '';

          // Cek jika jumlah notifikasi baru lebih banyak dari jumlah terakhir
          if (jumlah > lastNotificationCount) {
            playAudio();
          }

          // Update jumlah notifikasi terakhir di localStorage
          lastNotificationCount = jumlah;
          localStorage.setItem('lastNotificationCount', lastNotificationCount);

          // Tambahkan notifikasi ke daftar
          notifikasi.forEach(notif => {
            const listItem = `
              <li>
                <a>
                  <span class="image"><i class="fa fa-volume-up"></i></span>
                  <span>
                    <span><strong>&nbsp;Pendaftar Baru! </strong></span>
                    <span class="time">${new Date(notif.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</span>
                    <br>
                    <span class="image">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span>${notif.nama_pendaftar} (ID: ${notif.idnotifikasi})</span>
                    <br>
                    <span class="image">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <span>${new Date(notif.created_at).toLocaleDateString()}</span>
                    <br>
                    <span style="float: right;">Selengkapnya</span>
                  </span>
                </a>
              </li>
            `;
            menuList.innerHTML += listItem;
          });
        }
      })
      .catch(error => console.error('Error loading notifications:', error));
  }

  // Set interval to check for notifications every second
  setInterval(loadNotifications, 1000);

</script>

 <li role="presentation" class="dropdown">
                  <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bell-o"></i>
                  
                    <span class="badge bg-green"></span>
                  
                  </a>
                  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                        <li>
                          <a>
                            <span class="image"><i class="fa fa-volume-up"></i></span>
                            <span>
                              <span><strong>Pendaftar Baru!</strong></span>
                              <span class="time">waktu</span>
                              <br>
                               <span class="image">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                              <span>nama (ID: id)</span>
                              <br>
                              <span></span>
                              
                            </span>
                           
                          </a>
                        </li>
               
                       
                
                  </ul>
                </li>





                
              </ul>
            </nav>
          </div>
        </div>
@endsection