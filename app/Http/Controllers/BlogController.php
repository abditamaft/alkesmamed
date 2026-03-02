<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Data Dummy Artikel
    private $articles = [
        [
            'id' => 1,
            'title' => 'The Age of Analytics',
            'cat' => 'KESEHATAN 5.0',
            'date' => '21 April 2020',
            'author' => 'Owen Christ',
            'views' => 453,
            'image' => 'gambar_berita.jpg',
            'content' => 'Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga...',
            'tags' => ['analytics', 'hospital'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 2,
            'title' => 'Terbaru Coronavirus: Sekilas pandang',
            'cat' => 'BERITA HANGAT',
            'date' => '20 April 2020',
            'author' => 'Admin',
            'views' => 1020,
            'image' => 'gambar_berita.jpg',
            'content' => 'Update terbaru mengenai penyebaran virus corona di wilayah Asia Pasifik...',
            'tags' => ['covid19', 'virus'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 3,
            'title' => 'Pekerja kesehatan menghadapi pengunjuk rasa',
            'cat' => 'BERITA HANGAT',
            'date' => '19 April 2020',
            'author' => 'Dr. Sarah',
            'views' => 300,
            'image' => 'gambar_berita.jpg',
            'content' => 'Tenaga medis di garis depan menghadapi tantangan baru...',
            'tags' => ['kesehatan', 'demo'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 4,
            'title' => 'Pekerja kesehatan menghadapi pengunjuk rasa',
            'cat' => 'BERITA HANGAT',
            'date' => '19 April 2020',
            'author' => 'Dr. Sarah',
            'views' => 300,
            'image' => 'gambar_berita.jpg',
            'content' => 'Tenaga medis di garis depan menghadapi tantangan baru...',
            'tags' => ['kesehatan', 'demo'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 5,
            'title' => 'Terbaru Coronavirus: Sekilas pandang',
            'cat' => 'BERITA HANGAT',
            'date' => '20 April 2020',
            'author' => 'Admin',
            'views' => 1020,
            'image' => 'gambar_berita.jpg',
            'content' => 'Update terbaru mengenai penyebaran virus corona di wilayah Asia Pasifik...',
            'tags' => ['covid19', 'virus'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 6,
            'title' => 'The Age of Analytics',
            'cat' => 'KESEHATAN 5.0',
            'date' => '21 April 2020',
            'author' => 'Owen Christ',
            'views' => 453,
            'image' => 'gambar_berita.jpg',
            'content' => 'Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga...',
            'tags' => ['analytics', 'hospital'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 7,
            'title' => 'Terbaru Coronavirus: Sekilas pandang',
            'cat' => 'BERITA HANGAT',
            'date' => '20 April 2020',
            'author' => 'Admin',
            'views' => 1020,
            'image' => 'gambar_berita.jpg',
            'content' => 'Update terbaru mengenai penyebaran virus corona di wilayah Asia Pasifik...',
            'tags' => ['covid19', 'virus'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
        [
            'id' => 8,
            'title' => 'Pekerja kesehatan menghadapi pengunjuk rasa',
            'cat' => 'BERITA HANGAT',
            'date' => '19 April 2020',
            'author' => 'Dr. Sarah',
            'views' => 300,
            'image' => 'gambar_berita.jpg',
            'content' => 'Tenaga medis di garis depan menghadapi tantangan baru...',
            'tags' => ['kesehatan', 'demo'],
            'content' => '
            <blockquote class="border-l-4 border-blue-500 pl-6 py-2 my-8">
                    <p class="font-bold text-gray-800 text-lg italic">
                        "Data merupakan fondasi bagi masa depan perawatan kesehatan sebagai sumber daya yang berharga."
                    </p>
                </blockquote>

                <p class="mb-6">
                    Mengalami pergeseran paradigma perubahan yang cepat, layanan kesehatan Asia memasuki sistem perawatan kesehatan digital yang akan menggantikan pendahulunya di Industri 4.0. Segera melampaui Eropa sebagai pasar regional terbesar kedua untuk perangkat medis dan farmasi.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Dibangun untuk Mobilitas</h3>
                <p class="mb-6">
                    Selain perannya di sektor industri, exoskeleton telah berkembang ke ruangnya sendiri di sektor medis, menjadi solusi yang layak dalam rehabilitasi dan fisioterapi. Exoskeleton dapat membantu pasien stroke mendapatkan kembali mobilitas di antara pasien.
                </p>

                <h3 class="text-2xl font-bold text-gray-900 mt-10 mb-4">Akses ke Udara Berkualitas</h3>
                <p class="mb-6">
                    Sindrom gangguan pernapasan akut (ARDS) umum terjadi pada pasien yang sakit kritis, terkait erat dengan sepsis akut, pneumonia, aspirasi isi lambung, atau trauma berat. ARDS terjadi paling sering pada pasien yang dirawat di unit perawatan intensif (ICU).
                </p>
            '
        ],
    ];

    public function index()
    {
        // Kirim data untuk list artikel & juga untuk sidebar "Posting Populer"
        return view('blog', [
            'articles' => $this->articles,
            'popular_posts' => array_slice($this->articles, 0, 3) // Ambil 3 pertama untuk sidebar
        ]);
    }

    public function show($id)
    {
        // Cari artikel berdasarkan ID
        $article = collect($this->articles)->firstWhere('id', $id);

        if (!$article) {
            abort(404);
        }

        return view('detail-blog', [
            'article' => $article,
            'popular_posts' => array_slice($this->articles, 0, 3) // Sidebar juga butuh data ini
        ]);
    }
}