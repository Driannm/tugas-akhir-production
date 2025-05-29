@extends('app')

@section('content')
    <!-- Header with Navigation -->
    <header class="bg-white shadow-sm dark:bg-gray-800 sticky top-0 z-50">
        <nav class="px-4 lg:px-6 py-3 max-w-7xl mx-auto">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo/logo.png') }}" class="h-8 sm:h-10" alt="Ajuna Logo" />
                    <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">Ajuna Property</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features"
                        class="text-gray-700 hover:text-yellow-400 dark:text-gray-300 dark:hover:text-white transition">Fitur</a>
                    <a href="#benefits"
                        class="text-gray-700 hover:text-yellow-400 dark:text-gray-300 dark:hover:text-white transition">Keunggulan</a>
                    <a href="#testimonials"
                        class="text-gray-700 hover:text-yellow-400 dark:text-gray-300 dark:hover:text-white transition">Testimoni</a>
                    <a href="/main/login"
                        class="px-4 py-2 bg-yellow-400 text-gray-900 rounded-lg font-medium hover:bg-yellow-500 transition">Masuk</a>
                </div>
                <button class="md:hidden text-gray-700 dark:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                        </path>
                    </svg>
                </button>
            </div>
        </nav>
    </header>

    <!-- Hero Section with Image -->
    <section class="bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 py-12 md:py-24 lg:flex lg:items-center lg:gap-12">
            <div class="lg:w-1/2">
                <div class="inline-flex items-center px-4 py-2 mb-6 bg-yellow-50 rounded-full dark:bg-gray-800">
                    <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">
                        Selamat Datang di Sistem Management Proyek
                    </span>
                </div>

                <h1
                    class="mb-6 text-4xl font-extrabold tracking-tight leading-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">
                    Bangun Masa Depan Properti dengan Sistem Terintegrasi
                </h1>

                <p class="mb-8 text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400">
                    Di Ajuna Property, kami menghadirkan solusi inovatif dalam pengelolaan proyek properti. Dengan teknologi
                    dan strategi terbaik, kami memastikan setiap proyek berjalan efisien, transparan, dan bernilai tinggi
                    bagi semua pemangku kepentingan.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/main/login"
                        class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 bg-yellow-400 rounded-lg hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 dark:focus:ring-yellow-900 transition">
                        Masuk Sistem
                        <svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <a href="#contact"
                        class="inline-flex items-center justify-center px-5 py-3 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800 transition">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="lg:w-1/2 mt-12 lg:mt-0">
                <img src="{{ asset('images/hero-image.png') }}" alt="Property Management Dashboard"
                    class="w-full rounded-xl shadow-xl dark:shadow-gray-800/50">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-gray-50 dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                    Fitur Unggulan Sistem Kami
                </h2>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 mx-auto">
                    Solusi komprehensif untuk manajemen proyek properti Anda
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Proyek Terpusat</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Pantau seluruh proyek properti Anda dalam satu dashboard terintegrasi dengan timeline dan progress
                        yang real-time.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Penjadwalan Otomatis</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Sistem penjadwalan cerdas yang membantu Anda mengoptimalkan alokasi sumber daya dan memenuhi tenggat
                        waktu.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Analisis Risiko</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Identifikasi potensi risiko proyek secara dini dengan alat analisis prediktif kami untuk mitigasi
                        yang lebih baik.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Manajemen Anggaran</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Pantau pengeluaran proyek secara real-time dengan alat pelacakan anggaran yang akurat dan
                        terperinci.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Kolaborasi Tim</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Platform komunikasi terintegrasi untuk seluruh tim, kontraktor, dan pemangku kepentingan proyek.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <div
                        class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mb-4 dark:bg-yellow-900/30">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Laporan Otomatis</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Hasilkan laporan proyek profesional secara otomatis dengan berbagai template yang dapat disesuaikan.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- Benefits Section -->
    <section id="benefits" class="bg-white dark:bg-gray-900 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="lg:flex lg:items-center lg:gap-12">
                <div class="lg:w-1/2 mb-12 lg:mb-0">
                    <img src="{{ asset('images/benefits-image.png') }}" alt="Project Management Benefits"
                        class="w-full rounded-xl shadow-xl dark:shadow-gray-800/50">
                </div>
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl mb-6">
                        Keunggulan Menggunakan Sistem Kami
                    </h2>

                    <div class="space-y-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Efisiensi Waktu 40% Lebih
                                    Baik</h3>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">
                                    Otomatisasi proses manual mengurangi waktu administrasi hingga 40%, memungkinkan fokus
                                    pada aspek strategis proyek.
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Pengurangan Biaya Tak Terduga
                                </h3>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">
                                    Sistem prediktif kami membantu mengidentifikasi potensi pembengkakan biaya sebelum
                                    terjadi, menghemat hingga 15% anggaran proyek.
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="flex-shrink-0">
                                <div
                                    class="flex items-center justify-center h-8 w-8 rounded-full bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d=" M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Peningkatan Transparansi
                                    Proyek</h3>
                                <p class="mt-1 text-gray-600 dark:text-gray-400">
                                    Semua data proyek dapat diakses secara real-time oleh pemangku kepentingan, meningkatkan
                                    komunikasi dan pengambilan keputusan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="bg-gray-50 dark:bg-gray-800 py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl mb-12">
                Apa Kata Mereka
            </h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        "Sistem Ajuna Property sangat membantu kami dalam mengelola proyek dengan lebih efisien dan tepat
                        waktu."
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <img src="{{ asset('images/testimonials/user1.jpg') }}" alt="User 1"
                            class="w-12 h-12 rounded-full object-cover">
                        <div class="text-left">
                            <p class="text-gray-900 dark:text-white font-bold">Budi Santoso</p>
                            <p class="text-yellow-400">Manajer Proyek</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        "Fitur penjadwalan otomatis dan pelaporan membuat pekerjaan kami jadi lebih mudah dan terstruktur."
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <img src="{{ asset('images/testimonials/user2.jpg') }}" alt="User 2"
                            class="w-12 h-12 rounded-full object-cover">
                        <div class="text-left">
                            <p class="text-gray-900 dark:text-white font-bold">Sari Dewi</p>
                            <p class="text-yellow-400">Kepala Keuangan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md dark:bg-gray-700 transition hover:shadow-lg">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        "Kolaborasi tim jadi seamless berkat platform ini. Sangat direkomendasikan!"
                    </p>
                    <div class="flex items-center justify-center space-x-4">
                        <img src="{{ asset('images/testimonials/user3.jpg') }}" alt="User 3"
                            class="w-12 h-12 rounded-full object-cover">
                        <div class="text-left">
                            <p class="text-gray-900 dark:text-white font-bold">Agus Rahman</p>
                            <p class="text-yellow-400">Kontraktor</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
@endsection
