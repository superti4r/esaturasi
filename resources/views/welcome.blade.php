<!DOCTYPE html>
<html lang="id" class="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-Saturasi &mdash; SMKN 1 Sumberasih</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            DEFAULT: '#3b82f6',
                            hover: '#2563eb',
                        },
                    },
                    keyframes: {
                        gridMove: {
                            '0%': {
                                backgroundPosition: '0 0, 0 0'
                            },
                            '100%': {
                                backgroundPosition: '0 20px, 0 20px'
                            },
                        },
                        fadeIn: {
                            '0%': {
                                opacity: '0'
                            },
                            '100%': {
                                opacity: '1'
                            },
                        },
                    },
                    animation: {
                        gridLoop: 'gridMove 1s linear infinite',
                        fadeIn: 'fadeIn 1s ease forwards',
                    },
                },
            },
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body class="bg-white text-gray-900 dark:bg-zinc-950 dark:text-zinc-100 font-sans transition-colors duration-300">

    <header class="w-full border-b dark:border-zinc-800 p-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold"></h1>
        <div class="flex items-center gap-2">
            <a href="https://github.com/superti4r/esaturasiv2" target="_blank" rel="noopener noreferrer"
                class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition" aria-label="GitHub">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5" />
                </svg>
            </a>
            <button id="toggle-theme" class="p-2 rounded-md hover:bg-zinc-100 dark:hover:bg-zinc-800 transition"
                aria-label="Toggle Theme">
                <span id="theme-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </span>
            </button>
        </div>
    </header>

    <section class="relative overflow-hidden py-32 text-center bg-white dark:bg-gray-950 min-h-screen">
        <div
            class="absolute inset-0 z-0 pointer-events-none animate-gridLoop
    [background-image:repeating-linear-gradient(0deg,rgba(0,0,0,0.05)_0px,rgba(0,0,0,0.05)_1px,transparent_1px,transparent_20px),repeating-linear-gradient(90deg,rgba(0,0,0,0.05)_0px,rgba(0,0,0,0.05)_1px,transparent_1px,transparent_20px)]
    dark:[background-image:repeating-linear-gradient(0deg,rgba(255,255,255,0.07)_0px,rgba(255,255,255,0.07)_1px,transparent_1px,transparent_20px),repeating-linear-gradient(90deg,rgba(255,255,255,0.07)_0px,rgba(255,255,255,0.07)_1px,transparent_1px,transparent_20px)]">
        </div>
        <div class="relative z-10 max-w-4xl mx-auto px-6">
            <img src="{{ asset('img/logo.png') }}" alt="logo" class="mx-auto mb-6 w-24 h-24">
            <h2 class="text-4xl font-bold tracking-tight mb-4">E-Saturasi</h2>
            <p class="text-lg text-zinc-600 dark:text-zinc-400 mb-6">
                Siap menghadapi masa depan! Kami membekali siswa dengan keahlian vokasi terkini dan pengalaman belajar
                inovatif, agar siap bersaing di dunia kerja.
            </p>
            <div class="flex justify-center gap-2 flex-wrap mb-8">
                <span
                    class="inline-flex items-center gap-2 bg-zinc-200 dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-200 px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Laravel
                </span>
                <span
                    class="inline-flex items-center gap-2 bg-zinc-200 dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-200 px-3 py-1 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    Flutter
                </span>
            </div>
            <div class="flex justify-center gap-4">
                <a href="#"
                    class="inline-flex items-center gap-2 bg-primary text-white font-medium px-6 py-3 rounded-xl shadow hover:bg-primary-hover transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Download
                </a>
                <a href="/m"
                    class="inline-flex items-center gap-2 bg-primary text-white font-medium px-6 py-3 rounded-xl shadow hover:bg-primary-hover transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    Masuk
                </a>
            </div>
        </div>
    </section>

    <section class="px-6 py-20 bg-zinc-50 dark:bg-zinc-900">
        <div class="max-w-5xl mx-auto text-center">
            <div class="inline-flex items-center justify-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-full overflow-hidden border-3 border-primary">
                    <img src="{{ asset('img/KS.jpg') }}" alt="Avatar" class="w-full h-full object-cover" />
                </div>
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 select-none">
                    Kepala Sekolah :
                </h3>
            </div>
            <div class="max-w-3xl mx-auto bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl shadow-lg p-10
                   opacity-0 animate-fadeIn animation-delay-300"
                style="animation-delay: 0.3s;">
                <p class="text-lg text-zinc-700 dark:text-zinc-300 leading-relaxed tracking-wide">
                    SMKN 1 Sumberasih merupakan sekolah kejuruan pertama kali di wilayah bagian barat Kabupaten
                    Probolinggo yang berlokasi di Jl. Brawijaya No 78 Lemahkembar Kecamatan Sumberasih. Hal ini
                    bertujuan agar siswa/siswi lulusan SMP wilayah barat Kabupaten Probolinggo yang ingin melanjutkan
                    ke jenjang pendidikan lebih tinggi tidak perlu sekolah ke Kota Probolinggo mengingat jaraknya
                    cukup jauh. Selain itu bertujuan untuk memberikan peluang kerja bagi calon guru, karyawan
                    maupun guru yang belum mendapatkan tempat kerja.
                </p>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden py-28 text-center bg-white dark:bg-gray-950 min-h-screen">
        <div
            class="absolute inset-0 z-0 pointer-events-none animate-gridLoop
    [background-image:repeating-linear-gradient(0deg,rgba(0,0,0,0.05)_0px,rgba(0,0,0,0.05)_1px,transparent_1px,transparent_20px),repeating-linear-gradient(90deg,rgba(0,0,0,0.05)_0px,rgba(0,0,0,0.05)_1px,transparent_1px,transparent_20px)]
    dark:[background-image:repeating-linear-gradient(0deg,rgba(255,255,255,0.07)_0px,rgba(255,255,255,0.07)_1px,transparent_1px,transparent_20px),repeating-linear-gradient(90deg,rgba(255,255,255,0.07)_0px,rgba(255,255,255,0.07)_1px,transparent_1px,transparent_20px)]">
        </div>
        <div class="relative z-10 px-6">
            <h3 class="text-3xl font-bold text-center mb-14 text-zinc-900 dark:text-zinc-100">Fitur</h3>
            <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-8 rounded-3xl shadow-lg border border-zinc-200 dark:border-zinc-700 text-center transition-transform hover:scale-105">
                    <div class="text-zinc-900 dark:text-zinc-100 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-10 h-10 mx-auto">
                            <path
                                d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 1 .707A8.237 8.237 0 0 1 6 18.75c1.995 0 3.823.707 5.25 1.886V4.533ZM12.75 20.636A8.214 8.214 0 0 1 18 18.75c.966 0 1.89.166 2.75.47a.75.75 0 0 0 1-.708V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533v16.103Z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold mb-3 text-zinc-900 dark:text-zinc-100">Materi & Tugas</h4>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">Sangat mudah dipahami oleh siswa dan
                        dikelola sederhana oleh guru.</p>
                </div>
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-8 rounded-3xl shadow-lg border border-zinc-200 dark:border-zinc-700 text-center transition-transform hover:scale-105">
                    <div class="text-zinc-900 dark:text-zinc-100 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-10 h-10 mx-auto">
                            <path
                                d="M12 11.993a.75.75 0 0 0-.75.75v.006c0 .414.336.75.75.75h.006a.75.75 0 0 0 .75-.75v-.006a.75.75 0 0 0-.75-.75H12ZM12 16.494a.75.75 0 0 0-.75.75v.005c0 .414.335.75.75.75h.005a.75.75 0 0 0 .75-.75v-.005a.75.75 0 0 0-.75-.75H12ZM8.999 17.244a.75.75 0 0 1 .75-.75h.006a.75.75 0 0 1 .75.75v.006a.75.75 0 0 1-.75.75h-.006a.75.75 0 0 1-.75-.75v-.006ZM7.499 16.494a.75.75 0 0 0-.75.75v.005c0 .414.336.75.75.75h.005a.75.75 0 0 0 .75-.75v-.005a.75.75 0 0 0-.75-.75H7.5ZM13.499 14.997a.75.75 0 0 1 .75-.75h.006a.75.75 0 0 1 .75.75v.005a.75.75 0 0 1-.75.75h-.006a.75.75 0 0 1-.75-.75v-.005ZM14.25 16.494a.75.75 0 0 0-.75.75v.006c0 .414.335.75.75.75h.005a.75.75 0 0 0 .75-.75v-.006a.75.75 0 0 0-.75-.75h-.005ZM15.75 14.995a.75.75 0 0 1 .75-.75h.005a.75.75 0 0 1 .75.75v.006a.75.75 0 0 1-.75.75H16.5a.75.75 0 0 1-.75-.75v-.006ZM13.498 12.743a.75.75 0 0 1 .75-.75h2.25a.75.75 0 1 1 0 1.5h-2.25a.75.75 0 0 1-.75-.75ZM6.748 14.993a.75.75 0 0 1 .75-.75h4.5a.75.75 0 0 1 0 1.5h-4.5a.75.75 0 0 1-.75-.75Z" />
                            <path fill-rule="evenodd"
                                d="M18 2.993a.75.75 0 0 0-1.5 0v1.5h-9V2.994a.75.75 0 1 0-1.5 0v1.497h-.752a3 3 0 0 0-3 3v11.252a3 3 0 0 0 3 3h13.5a3 3 0 0 0 3-3V7.492a3 3 0 0 0-3-3H18V2.993ZM3.748 18.743v-7.5a1.5 1.5 0 0 1 1.5-1.5h13.5a1.5 1.5 0 0 1 1.5 1.5v7.5a1.5 1.5 0 0 1-1.5 1.5h-13.5a1.5 1.5 0 0 1-1.5-1.5Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold mb-3 text-zinc-900 dark:text-zinc-100">Penjadwalan</h4>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">Dikelola dengan sederhana guna dipahami
                        oleh siswa dan guru.</p>
                </div>
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-8 rounded-3xl shadow-lg border border-zinc-200 dark:border-zinc-700 text-center transition-transform hover:scale-105">
                    <div class="text-zinc-900 dark:text-zinc-100 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-10 h-10 mx-auto">
                            <path fill-rule="evenodd"
                                d="M12 1.5a.75.75 0 0 1 .75.75V4.5a.75.75 0 0 1-1.5 0V2.25A.75.75 0 0 1 12 1.5ZM5.636 4.136a.75.75 0 0 1 1.06 0l1.592 1.591a.75.75 0 0 1-1.061 1.06l-1.591-1.59a.75.75 0 0 1 0-1.061Zm12.728 0a.75.75 0 0 1 0 1.06l-1.591 1.592a.75.75 0 0 1-1.06-1.061l1.59-1.591a.75.75 0 0 1 1.061 0Zm-6.816 4.496a.75.75 0 0 1 .82.311l5.228 7.917a.75.75 0 0 1-.777 1.148l-2.097-.43 1.045 3.9a.75.75 0 0 1-1.45.388l-1.044-3.899-1.601 1.42a.75.75 0 0 1-1.247-.606l.569-9.47a.75.75 0 0 1 .554-.68ZM3 10.5a.75.75 0 0 1 .75-.75H6a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 10.5Zm14.25 0a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5H18a.75.75 0 0 1-.75-.75Zm-8.962 3.712a.75.75 0 0 1 0 1.061l-1.591 1.591a.75.75 0 1 1-1.061-1.06l1.591-1.592a.75.75 0 0 1 1.06 0Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold mb-3 text-zinc-900 dark:text-zinc-100">UI/UX Simply</h4>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">Design yang user-friendly & Interaktif,
                        mudah sekali dipahami.</p>
                </div>
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-8 rounded-3xl shadow-lg border border-zinc-200 dark:border-zinc-700 text-center transition-transform hover:scale-105">
                    <div class="text-zinc-900 dark:text-zinc-100 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-10 h-10 mx-auto">
                            <path fill-rule="evenodd"
                                d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Zm3.094 8.016a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold mb-3 text-zinc-900 dark:text-zinc-100">Keamanan Data</h4>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">Menggunakan keamanan data yang
                        signifikan sesuai standar internasional.</p>
                </div>
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-8 rounded-3xl shadow-lg border border-zinc-200 dark:border-zinc-700 text-center transition-transform hover:scale-105">
                    <div class="text-zinc-900 dark:text-zinc-100 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-10 h-10 mx-auto">
                            <path
                                d="M16.881 4.345A23.112 23.112 0 0 1 8.25 6H7.5a5.25 5.25 0 0 0-.88 10.427 21.593 21.593 0 0 0 1.378 3.94c.464 1.004 1.674 1.32 2.582.796l.657-.379c.88-.508 1.165-1.593.772-2.468a17.116 17.116 0 0 1-.628-1.607c1.918.258 3.76.75 5.5 1.446A21.727 21.727 0 0 0 18 11.25c0-2.414-.393-4.735-1.119-6.905ZM18.26 3.74a23.22 23.22 0 0 1 1.24 7.51 23.22 23.22 0 0 1-1.41 7.992.75.75 0 1 0 1.409.516 24.555 24.555 0 0 0 1.415-6.43 2.992 2.992 0 0 0 .836-2.078c0-.807-.319-1.54-.836-2.078a24.65 24.65 0 0 0-1.415-6.43.75.75 0 1 0-1.409.516c.059.16.116.321.17.483Z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold mb-3 text-zinc-900 dark:text-zinc-100">Informasi Terupdate</h4>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">Siswa dapat selalu update dengan
                        informasi terbaru melalui aplikasi.</p>
                </div>
                <div
                    class="bg-zinc-100 dark:bg-zinc-800 p-8 rounded-3xl shadow-lg border border-zinc-200 dark:border-zinc-700 text-center transition-transform hover:scale-105">
                    <div class="text-zinc-900 dark:text-zinc-100 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-10 h-10 mx-auto">
                            <path
                                d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                            <path
                                d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-semibold mb-3 text-zinc-900 dark:text-zinc-100">Pengelolaan</h4>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">Mudahnya mengelola laporan dan data
                        serta penilaian terhadap siswa.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 py-20 max-w-3xl mx-auto text-center">
        <div class="bg-white dark:bg-zinc-950 p-6 rounded-xl border dark:border-zinc-800 shadow">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('img/KHD.jpg') }}" alt="Ki Hajar Dewantara"
                    class="w-16 h-16 rounded-full border dark:border-zinc-700 shadow-md object-cover">
            </div>
            <p class="italic text-zinc-600 dark:text-zinc-400 mb-4">
                "ing ngarsa sung tuladha ing madya mangun karsa tut wuri handayani"
            </p>
            <p class="font-medium">— Ki Hajar Dewantara, 6 September 1977</p>
        </div>
    </section>

    <footer class="border-t dark:border-zinc-800 text-center py-6 text-sm text-zinc-500 dark:text-zinc-400">
        &copy; 2025 Project Pintar. Semua hak dilindungi.
    </footer>

    <script>
        const toggleBtn = document.getElementById('toggle-theme');
        const iconSpan = document.getElementById('theme-icon');

        const moonIcon = `
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75
        0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
      </svg>`;

        const sunIcon = `
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386
        6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591
        1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75
        3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
      </svg>`;

        toggleBtn.addEventListener('click', () => {
            const html = document.documentElement;
            html.classList.toggle('dark');
            iconSpan.innerHTML = html.classList.contains('dark') ? sunIcon : moonIcon;
        });

        window.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            iconSpan.innerHTML = html.classList.contains('dark') ? sunIcon : moonIcon;
        });
    </script>

</body>

</html>
