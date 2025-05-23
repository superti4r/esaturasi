<?php

return [
    'password_confirm' => [
        'heading' => 'Konfirmasi Kata Sandi',
        'description' => 'Silakan konfirmasi kata sandi Anda untuk menyelesaikan tindakan ini.',
        'current_password' => 'Kata sandi saat ini',
    ],
    'two_factor' => [
        'heading' => 'Tantangan Autentikasi Dua Faktor',
        'description' => 'Silakan konfirmasi akses ke akun Anda dengan memasukkan kode dari aplikasi autentikator Anda.',
        'code_placeholder' => 'XXX-XXX',
        'recovery' => [
            'heading' => 'Tantangan Autentikasi Dua Faktor',
            'description' => 'Silakan konfirmasi akses ke akun Anda dengan memasukkan salah satu kode pemulihan darurat Anda.',
        ],
        'recovery_code_placeholder' => 'abcdef-98765',
        'recovery_code_text' => 'Kehilangan perangkat?',
        'recovery_code_link' => 'Gunakan kode pemulihan',
        'back_to_login_link' => 'Kembali ke login',
    ],
    'profile' => [
        'account' => 'Akun',
        'profile' => 'Profil',
        'my_profile' => 'Profil Saya',
        'subheading' => 'Kelola profil pengguna Anda di sini.',
        'personal_info' => [
            'heading' => 'Informasi Pribadi',
            'subheading' => 'Kelola informasi pribadi Anda.',
            'submit' => [
                'label' => 'Perbarui',
            ],
            'notify' => 'Profil berhasil diperbarui!',
        ],
        'password' => [
            'heading' => 'Kata Sandi',
            'subheading' => 'Harus terdiri dari minimal 8 karakter.',
            'submit' => [
                'label' => 'Perbarui',
            ],
            'notify' => 'Kata sandi berhasil diperbarui!',
        ],
        '2fa' => [
            'title' => 'Autentikasi Dua Faktor',
            'description' => 'Kelola autentikasi dua faktor untuk akun Anda (disarankan).',
            'actions' => [
                'enable' => 'Aktifkan',
                'regenerate_codes' => 'Buat Ulang Kode Pemulihan',
                'disable' => 'Nonaktifkan',
                'confirm_finish' => 'Konfirmasi & selesai',
                'cancel_setup' => 'Batalkan pengaturan',
            ],
            'setup_key' => 'Kunci pengaturan',
            'must_enable' => 'Anda harus mengaktifkan Autentikasi Dua Faktor untuk menggunakan aplikasi ini.',
            'not_enabled' => [
                'title' => 'Anda belum mengaktifkan autentikasi dua faktor.',
                'description' => 'Saat autentikasi dua faktor diaktifkan, Anda akan diminta untuk memasukkan token acak yang aman selama proses login. Anda dapat menggunakan aplikasi autentikator di ponsel Anda seperti Google Authenticator, Microsoft Authenticator, dll.',
            ],
            'finish_enabling' => [
                'title' => 'Selesaikan aktivasi autentikasi dua faktor.',
                'description' => 'Untuk menyelesaikan aktivasi autentikasi dua faktor, pindai kode QR berikut menggunakan aplikasi autentikator di ponsel Anda atau masukkan kunci pengaturan dan kode OTP yang dihasilkan.',
            ],
            'enabled' => [
                'notify' => 'Autentikasi dua faktor telah diaktifkan.',
                'title' => 'Anda telah mengaktifkan autentikasi dua faktor!',
                'description' => 'Autentikasi dua faktor sekarang diaktifkan. Ini membantu menjaga keamanan akun Anda.',
                'store_codes' => 'Kode-kode ini dapat digunakan untuk memulihkan akses ke akun Anda jika perangkat Anda hilang. Peringatan! Kode ini hanya akan ditampilkan sekali.',
            ],
            'disabling' => [
                'notify' => 'Autentikasi dua faktor telah dinonaktifkan.',
            ],
            'regenerate_codes' => [
                'notify' => 'Kode pemulihan baru telah dibuat.',
            ],
            'confirmation' => [
                'success_notification' => 'Kode berhasil diverifikasi. Autentikasi dua faktor diaktifkan.',
                'invalid_code' => 'Kode yang Anda masukkan tidak valid.',
            ],
        ],
        'sanctum' => [
            'title' => 'Token API',
            'description' => 'Kelola token API yang memungkinkan layanan pihak ketiga mengakses aplikasi ini atas nama Anda.',
            'create' => [
                'notify' => 'Token berhasil dibuat!',
                'message' => 'Token Anda hanya ditampilkan sekali saat pembuatan. Jika Anda kehilangan token, Anda harus menghapus dan membuat token baru.',
                'submit' => [
                    'label' => 'Buat',
                ],
            ],
            'update' => [
                'notify' => 'Token berhasil diperbarui!',
                'submit' => [
                    'label' => 'Perbarui',
                ],
            ],
            'copied' => [
                'label' => 'Saya sudah menyalin token saya',
            ],
        ],
        'browser_sessions' => [
            'title' => 'Sesi Browser',
            'heading' => 'Sesi Browser',
            'subheading' => 'Kelola dan keluar dari sesi aktif Anda di browser dan perangkat lain.',
            'description' => 'Kelola sesi aktif Anda.',
            'label' => 'Sesi Browser',
            'content' => 'Jika perlu, Anda dapat keluar dari semua sesi browser lain di semua perangkat Anda. Beberapa sesi terakhir Anda tercantum di bawah ini; namun, daftar ini mungkin tidak lengkap. Jika Anda merasa akun Anda telah dikompromikan, sebaiknya perbarui kata sandi Anda.',
            'device' => 'Perangkat ini',
            'last_active' => 'Terakhir aktif',
            'logout_other_sessions' => 'Keluar dari Sesi Browser Lain',
            'logout_heading' => 'Keluar dari Sesi Browser Lain',
            'logout_description' => 'Silakan masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin keluar dari semua sesi browser lain di semua perangkat Anda.',
            'logout_action' => 'Keluar dari Sesi Browser Lain',
            'incorrect_password' => 'Kata sandi yang Anda masukkan salah. Silakan coba lagi.',
            'logout_success' => 'Semua sesi browser lain telah berhasil keluar.',
        ],
    ],
    'clipboard' => [
        'link' => 'Salin ke clipboard',
        'tooltip' => 'Disalin!',
    ],
    'fields' => [
        'avatar' => 'Avatar',
        'email' => 'Email',
        'login' => 'Masuk',
        'name' => 'Nama',
        'password' => 'Kata Sandi',
        'password_confirm' => 'Konfirmasi Kata Sandi',
        'new_password' => 'Kata Sandi Baru',
        'new_password_confirmation' => 'Konfirmasi Kata Sandi Baru',
        'token_name' => 'Nama Token',
        'token_expiry' => 'Kadaluarsa Token',
        'abilities' => 'Kemampuan',
        '2fa_code' => 'Kode',
        '2fa_recovery_code' => 'Kode Pemulihan',
        'created' => 'Dibuat',
        'expires' => 'Kadaluarsa',
    ],
    'or' => 'Atau',
    'cancel' => 'Batal',
];
