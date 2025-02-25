<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>E-Saturasi &mdash; SMK Negeri 1 Sumberasih</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="{{ asset('_root/img/favicon.ico')}}" rel="icon">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Jost:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  <link href="{{ asset('_root/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ asset('_root/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{ asset('_root/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{ asset('_root/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{ asset('_root/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link href="{{ asset('_root/css/main.css')}}" rel="stylesheet">
  <link href="{{ asset('_root/css/chatbot.css')}}" rel="stylesheet">
</head>
<body class="index-page">
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">
      <a href="/" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">E-Saturasi</h1>
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="/" class="active">Home</a></li>
          <li class="dropdown"><a href="#"><span>Quick Link</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
            <ul>
              <li><a href="https://www.kemdikbud.go.id/">Kemdikbud</a></li>
              <li><a href="https://smkn1sumberasih-pbl.sch.id/">Kunjungi Website Sekolah</a></li>
            </ul>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="btn-getstarted" href="/login">Login</a>
    </div>
  </header>
  <main class="main">
    <section id="hero" class="hero section dark-background">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 order-2 order-lg-1 d-flex flex-column justify-content-center" data-aos="zoom-out">
            <h1>E-Saturasi</h1>
            <p>Sebuah Aplikasi E-Learning Management System Guna Mempermudah Proses Pembelajaran Untuk SMK Negeri 1 Sumberasih.</p>
            <div class="d-flex">
              <a href="#" class="btn-get-started">Download Aplikasi</a>
            </div>
          </div>
          <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-out" data-aos-delay="200">
            <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
              <dotlottie-player
                src="https://lottie.host/9b9f7e05-8b71-45f7-a0af-0e19e072f044/l7NT4cnlNT.lottie"
                background="transparent"
                speed="1"
                style="width: 400px; height: 400px"
                loop
                autoplay
              ></dotlottie-player>
            </div>
          </div>
        </div>
      </div>
    </section>
    <section id="clients" class="clients section light-background">
      <div class="container" data-aos="zoom-in">
        <div class="swiper init-swiper">
          <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
            <script type="application/json" class="swiper-config">
              {
                "loop": true,
                "speed": 600,
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": "auto",
                "pagination": {
                  "el": ".swiper-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "breakpoints": {
                  "320": {
                    "slidesPerView": 2,
                    "spaceBetween": 40
                  },
                  "480": {
                    "slidesPerView": 3,
                    "spaceBetween": 60
                  },
                  "640": {
                    "slidesPerView": 4,
                    "spaceBetween": 80
                  },
                  "992": {
                    "slidesPerView": 5,
                    "spaceBetween": 120
                  },
                  "1200": {
                    "slidesPerView": 6,
                    "spaceBetween": 120
                  }
                }
              }
            </script>
          <div class="text-center mb-3">
            <h3>Teknologi yang dipakai :</h3>
          </div>
          <div class="swiper-wrapper align-items-center">
            <div class="swiper-slide"><img src="{{ asset('_root/img/clients/flutter.webp')}}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('_root/img/clients/laravel.webp')}}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('_root/img/clients/dart.webp')}}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('_root/img/clients/php.webp')}}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('_root/img/clients/mysql.webp')}}" class="img-fluid" alt=""></div>
            <div class="swiper-slide"><img src="{{ asset('_root/img/clients/bootstrap.webp')}}" class="img-fluid" alt=""></div>
          </div>
      </div>
    </section>
    <section id="about" class="about section">
      <div class="container section-title" data-aos="fade-up">
        <h2>Tentang Kami</h2>
      </div>
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-6 content" data-aos="fade-up" data-aos-delay="100">
            <p>
              E-Saturasi adalah Aplikasi E-Learning berbasis Website dan Mobile yang terintegrasi guna mempermudah
              Siswa dan Guru dalam proses pembelajaran, dengan fitur :
            </p>
            <ul>
              <li><i class="bi bi-check2-circle"></i> <span>Manajemen data Siswa dan Guru</span></li>
              <li><i class="bi bi-check2-circle"></i> <span>Mempermudah Siswa dalam proses belajar</span></li>
              <li><i class="bi bi-check2-circle"></i> <span>Memanfaatkan teknologi sekarang dalam proses pembelajaran</span></li>
            </ul>
          </div>

          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <p>Sistem aplikasi ini dirancang guna mempermudah proses pembelajaran secara daring dengan keamanan yang aman dan nyaman, serta mempermudah segala hal bentuk pembelajaran yang menyokong SMK Negeri 1 Sumberasih menjadi sekolah modern sesuai motto SMK yaitu SMK Bisa, SMK Siap Kerja, Santun, Mandiri & Kreatif. </p>
          </div>
        </div>
      </div>
    </section>

    <div id="chatbot-toggle" class="chatbot-widget">
        <div class="chatbot-button">
          <dotlottie-player
            src="https://lottie.host/0c617fc4-7584-41ff-ac62-ab7e01306aa2/lydrHgewQK.lottie"
            background="transparent"
            speed="1"
            style="width: 40px; height: 40px"
            loop autoplay
          ></dotlottie-player>
        </div>
      </div>

      <div id="chatbot-modal" class="chatbot-modal hidden">
        <div class="chatbot-content">
          <div class="chatbot-header">
            <h5>Tanya si Satria yuk!</h5>
            <button id="chatbot-close">✖</button>
          </div>
          <div class="chatbot-messages" id="chatbot-messages"></div>
          <div class="chatbot-input">
            <input type="text" id="chatbot-user-input" placeholder="Ketik pesan..." />
            <button id="chatbot-send-btn">➤</button>
          </div>
        </div>
      </div>

      <script>
      document.getElementById("chatbot-send-btn").addEventListener("click", sendChatbotMessage);
      document.getElementById("chatbot-user-input").addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
          sendChatbotMessage();
        }
      });

      function sendChatbotMessage() {
        const userInput = document.getElementById("chatbot-user-input").value.trim();
        if (!userInput) return;

        addChatbotMessage(userInput, "chatbot-user");
        document.getElementById("chatbot-user-input").value = "";

        fetch("{{ route('chatbot') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
          },
          body: JSON.stringify({ message: userInput })
        })
          .then(response => response.json())
          .then(data => {
            addChatbotMessage(data.response, "chatbot-bot", "bot-lottie");
          })
          .catch(error => {
            console.error("Error:", error);
            addChatbotMessage("Maaf, terjadi kesalahan.", "chatbot-bot", "bot-lottie");
          });
      }

      function addChatbotMessage(text, sender, avatar) {
        const chatBox = document.getElementById("chatbot-messages");
        const messageContainer = document.createElement("div");
        messageContainer.classList.add("chatbot-message-container", sender.includes("user") ? "user" : "bot");

        const messageDiv = document.createElement("div");
        messageDiv.classList.add("chatbot-message", sender);
        messageDiv.innerText = text;

        if (avatar === "bot-lottie") {
          let avatarElement = document.createElement("div");
          avatarElement.innerHTML = `
            <dotlottie-player
              src="https://lottie.host/9a03c788-a616-498e-b7ef-a7e4e71e3756/1xtH5781ZI.lottie"
              background="transparent"
              speed="1"
              style="width: 50px; height: 50px"
              loop autoplay
            ></dotlottie-player>`;
          avatarElement.classList.add("chatbot-lottie");

          messageContainer.appendChild(avatarElement);
          messageContainer.appendChild(messageDiv);
        } else {
          messageContainer.appendChild(messageDiv);
        }

        chatBox.appendChild(messageContainer);
        chatBox.scrollTop = chatBox.scrollHeight;
      }

      document.getElementById("chatbot-toggle").addEventListener("click", function () {
        document.getElementById("chatbot-modal").classList.remove("hidden");
      });

      document.getElementById("chatbot-close").addEventListener("click", function () {
        document.getElementById("chatbot-modal").classList.add("hidden");
      });
      </script>
  </main>
  <footer id="footer" class="footer">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="/" class="d-flex align-items-center">
            <span class="sitename">E-Saturasi</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Jl. Lemahkembar, Sumberasih, Probolinggo</p>
            <p>East Java 67251, Indonesia - SMK NEGERI 1 SUMBERASIH</p>
            <p class="mt-3"><strong>Hubungi:</strong> <span>(0335) 435952</span></p>
            <p><strong>Email:</strong> <span>smknsumberasih@gmail.com</span></p>
          </div>
        </div>
        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Quick Link</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="/">Home</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="https://www.kemdikbud.go.id/">Kemdikbud</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="https://smkn1sumberasih-pbl.sch.id/">Web Sekolah</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="/login">Login</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">E-Saturasi</strong> <span>All Rights Reserved</span></p>
    </div>
  </footer>
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader"></div>

  <script src="{{ asset('_root/vendor/bootstrap/js/bootstrap.bundle.min.js')}}assets/"></script>
  <script src="{{ asset('_root/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{ asset('_root/vendor/aos/aos.js')}}"></script>
  <script src="{{ asset('_root/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{ asset('_root/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{ asset('_root/vendor/waypoints/noframework.waypoints.js')}}"></script>
  <script src="{{ asset('_root/vendor/imagesloaded/imagesloaded.pkgd.min.js')}}"></script>
  <script src="{{ asset('_root/vendor/isotope-layout/isotope.pkgd.min.js')}}"></script>
  <script src="{{ asset('_root/js/main.js')}}"></script>
</body>
</html>
