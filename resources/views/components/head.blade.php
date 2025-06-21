<head lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- SEO Meta -->
    <meta name="description"
        content="MindMapku adalah aplikasi digital pembuat peta pikiran yang mendorong kemampuan berpikir kritis melalui proses merangkum dan merefleksi bacaan secara interaktif.">
    <meta name="keywords"
        content="MindMap, Peta Pikiran, Pendidikan, E-Learning, Critical Reading, Ringkasan Teks, Refleksi, Spider Map, Flow Map, Brace Map, Bubble Map">
    <meta name="author" content="MindMapku Team">

    <!-- Open Graph Meta Tags -->
    <meta property="og:url" content="https://mindmapku.com">
    <meta property="og:type" content="website">
    <meta property="og:title" content="MindMapku - Digital Mind Mapping untuk Membaca Kritis">
    <meta property="og:description"
        content="Visualisasikan, ringkas, dan refleksikan bacaan secara kreatif dengan berbagai jenis peta pikiran seperti Spider Map, Flow Map, dan lainnya.">
    <meta property="og:image" content="https://mindmapku.com/og-preview.jpg">
    <meta property="og:image:width" content="600">
    <meta property="og:image:height" content="400">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="MindMapku - Peta Pikiran Interaktif untuk Membaca Kritis">
    <meta name="twitter:description"
        content="MindMapku mempermudah siswa memahami bacaan dengan membuat ringkasan dan refleksi secara visual dalam bentuk peta pikiran.">
    <meta name="twitter:image" content="https://mindmapku.com/og-preview.jpg">
    <meta property="twitter:domain" content="mindmapku.com">
    <meta property="twitter:url" content="https://mindmapku.com">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&family=Quicksand:wght@300..700&display=swap"
        rel="stylesheet">

    <!-- Styles and Scripts -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- Libraries & Plugins -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@next/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@next/dist/aos.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Mind Map Specific Libraries -->
    <script src="https://unpkg.com/vis-network@9.1.2/dist/vis-network.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Document & PDF Handling -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.50.0/docxtemplater.js"></script>
    <script src="https://unpkg.com/jszip/dist/jszip.min.js"></script>
    <script src="https://volodymyrbaydalka.github.io/docxjs/dist/docx-preview.min.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.7/dist/pizzip.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.7/dist/pizzip-utils.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/1.3.8/FileSaver.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.8.0/mammoth.browser.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.15.349/pdf.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- PWA Meta (optional if you plan to support PWA) -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">
    <!-- jsMind CSS -->
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsmind@0.4.6/style/jsmind.css" />

    <!-- jsMind JS -->
    <script type="text/javascript" src="https://unpkg.com/jsmind@0.8.7/es6/jsmind.js"></script>
    <script type="text/javascript" src="https://unpkg.com/jsmind@0.8.7/es6/jsmind.draggable-node.js"></script>
    <link type="text/css" rel="stylesheet" href="https://unpkg.com/jsmind@0.8.7/style/jsmind.css" />
    <script type="text/javascript" src="https://unpkg.com/dom-to-image@2.6.0/dist/dom-to-image.min.js"></script>

    <script type="text/javascript" src="https://unpkg.com/jsmind@0.8.7/es6/jsmind.screenshot.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- Title -->
    <title>MindMapku | {{ $title ?? 'Peta Pikiran Digital yang Interaktif' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .export-safe,
        .export-safe * {
            background-color: #ffffff !important;
            color: #000000 !important;
            border-color: #000000 !important;
            box-shadow: none !important;
            filter: none !important;
        }
    </style>
</head>
