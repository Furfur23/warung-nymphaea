<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kontak Kami — Warung Nymphaea</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body:    ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        cream:    '#FDF6EC',
                        bark:     '#5C3D2E',
                        ember:    '#D4622A',
                        saffron:  '#F2A035',
                        sage:     '#7A9E7E',
                        charcoal: '#2C2016',
                    },
                    keyframes: {
                        'slide-up': { '0%': { opacity:'0', transform:'translateY(20px)' }, '100%': { opacity:'1', transform:'translateY(0)' } },
                        'fade-in':  { '0%': { opacity:'0' }, '100%': { opacity:'1' } },
                    },
                    animation: {
                        'slide-up': 'slide-up 0.5s ease-out both',
                        'fade-in':  'fade-in 0.4s ease-out both',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #D4622A; border-radius: 3px; }

        /* Input focus style */
        .form-input:focus {
            outline: none;
            border-color: #D4622A;
            box-shadow: 0 0 0 3px rgba(212,98,42,0.12);
        }

        /* Reveal on scroll */
        .reveal { opacity: 0; transform: translateY(24px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }

        /* Nav link underline */
        .nav-link { position: relative; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:#F2A035; border-radius:2px; transition:width 0.3s ease; }
        .nav-link:hover::after, .nav-link.active::after { width:100%; }

        /* Map placeholder shimmer */
        @keyframes shimmer {
            0%   { background-position: -400px 0; }
            100% { background-position: 400px 0; }
        }
        .map-shimmer {
            background: linear-gradient(90deg, #e8ddd5 25%, #f0e8e0 50%, #e8ddd5 75%);
            background-size: 800px 100%;
            animation: shimmer 2s infinite;
        }

        /* Toast */
        @keyframes toast-in { from { opacity:0; transform: translateY(100%) scale(0.9); } to { opacity:1; transform: translateY(0) scale(1); } }
        .toast-enter { animation: toast-in 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    </style>
</head>

<body class="relative text-charcoal min-h-screen">

{{-- ═══════════════════════════════════════
     NAVBAR
═══════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-bark shadow-lg">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">

        <a href="{{ route('catalog.index') }}" class="flex items-center gap-3 flex-shrink-0 group">
            <div class="w-9 h-9 rounded-full bg-ember flex items-center justify-center text-cream font-display font-bold text-lg leading-none shadow-inner group-hover:scale-110 transition-transform">W</div>
            <span class="font-display text-cream text-xl tracking-wide">Warung<span class="text-saffron italic">Nymphaea</span></span>
        </a>

        <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-cream/70">
            <a href="{{ route('catalog.index') }}" class="nav-link hover:text-cream transition-colors">Menu</a>
            <a href="{{ route('catalog.index') }}#about" class="nav-link hover:text-cream transition-colors">Tentang Kami</a>
            <a href="{{ route('contact') }}" class="nav-link active hover:text-cream transition-colors text-cream">Kontak</a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ route('catalog.index') }}"
               class="flex items-center gap-2 bg-ember hover:bg-orange-600 text-cream px-4 py-2 rounded-full font-semibold text-sm transition-all hover:shadow-lg active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="hidden sm:inline">Kembali ke Menu</span>
            </a>

            <button id="mobile-menu-btn" class="md:hidden w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <svg id="icon-open" class="w-5 h-5 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-5 h-5 text-cream hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-charcoal border-t border-white/10">
        <nav class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('catalog.index') }}" class="text-cream/80 hover:text-cream hover:bg-white/10 font-semibold text-sm px-3 py-2.5 rounded-lg transition-colors">🍽️ Menu</a>
            <a href="{{ route('catalog.index') }}#about" class="text-cream/80 hover:text-cream hover:bg-white/10 font-semibold text-sm px-3 py-2.5 rounded-lg transition-colors">✨ Tentang Kami</a>
            <a href="{{ route('contact') }}" class="text-cream font-semibold text-sm px-3 py-2.5 rounded-lg bg-white/10">📍 Kontak</a>
        </nav>
    </div>
</header>


{{-- PAGE HEADER --}}
<div class="bg-gradient-to-br from-bark via-[#3D2314] to-charcoal text-cream py-12 px-4 relative overflow-hidden">
    <div class="absolute -right-12 -top-12 w-56 h-56 rounded-full border-[36px] border-saffron/10 pointer-events-none"></div>
    <div class="absolute -left-6 bottom-0 w-36 h-36 rounded-full border-[20px] border-ember/10 pointer-events-none"></div>
    <div class="max-w-6xl mx-auto relative z-10">
        <div class="flex items-center gap-2 text-cream/40 text-sm mb-2">
            <a href="{{ route('catalog.index') }}" class="hover:text-cream/70 transition-colors">Beranda</a>
            <span>›</span>
            <span class="text-cream/70">Kontak</span>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl animate-slide-up">
            Hubungi <span class="italic text-saffron">Kami</span>
        </h1>
        <p class="text-cream/55 text-sm sm:text-base max-w-md mt-2 animate-slide-up" style="animation-delay:0.1s">
            Kami senang mendengar dari kamu — untuk pemesanan, masukan, atau sekadar menyapa!
        </p>
    </div>
</div>


{{-- FLASH MESSAGE --}}
@if(session('success') || session('error'))
<div id="flash-toast"
     class="toast-enter fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl
            {{ session('success') ? 'bg-sage text-white' : 'bg-red-500 text-white' }}
            text-sm font-semibold max-w-sm w-full">
    <span class="text-lg">{{ session('success') ? '✅' : '❌' }}</span>
    <span>{{ session('success') ?? session('error') }}</span>
    <button onclick="document.getElementById('flash-toast').remove()" class="ml-auto text-white/70 hover:text-white">✕</button>
</div>
<script>setTimeout(() => { const t = document.getElementById('flash-toast'); if (t) t.remove(); }, 4000);</script>
@endif


{{-- ═══════════════════════════════════════
     MAIN CONTENT — 2 kolom
═══════════════════════════════════════ --}}
<main class="max-w-6xl mx-auto px-4 py-12 relative z-10">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-start">

        {{-- ════════════════════════════════
             KOLOM KIRI — Info Kontak + Peta
        ════════════════════════════════ --}}
        <div class="space-y-6">

            {{-- Info Cards --}}
            <div class="reveal">
                <h2 class="font-display text-2xl text-bark mb-5">
                    Informasi <span class="italic text-ember">Toko</span>
                </h2>

                @php
                $contacts = [
                    [
                        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>',
                        'label'  => 'Alamat',
                        'value'  => 'Jl. Melati No. 12, Kel. Kepuharjo,<br>Kec. Lumajang, Kab. Lumajang,<br>Jawa Timur 67311',
                        'href'   => null,
                        'bg'     => 'bg-ember/10',
                        'color'  => 'text-ember',
                    ],
                    [
                        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>',
                        'label'  => 'WhatsApp Admin',
                        'value'  => '0812-3456-7890',
                        'href'   => 'https://wa.me/6281234567890',
                        'bg'     => 'bg-[#25D366]/10',
                        'color'  => 'text-[#25D366]',
                    ],
                    [
                        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
                        'label'  => 'Email',
                        'value'  => 'warungnymphaea@gmail.com',
                        'href'   => 'mailto:warungnymphaea@gmail.com',
                        'bg'     => 'bg-saffron/10',
                        'color'  => 'text-saffron',
                    ],
                    [
                        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        'label'  => 'Jam Operasional',
                        'value'  => '<strong>Senin – Sabtu</strong>: 07.00 – 21.00 WIB<br><strong>Minggu & Hari Libur</strong>: 08.00 – 20.00 WIB',
                        'href'   => null,
                        'bg'     => 'bg-sage/10',
                        'color'  => 'text-sage',
                    ],
                ];
                @endphp

                <div class="space-y-3">
                    @foreach($contacts as $c)
                    <div class="bg-white rounded-2xl border border-bark/8 shadow-sm p-4 flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 {{ $c['color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                {!! $c['icon'] !!}
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-bark/40 text-xs font-bold uppercase tracking-wider mb-0.5">{{ $c['label'] }}</p>
                            @if($c['href'])
                                <a href="{{ $c['href'] }}" target="_blank"
                                   class="font-semibold text-bark hover:text-ember transition-colors text-sm leading-relaxed">
                                    {!! $c['value'] !!}
                                </a>
                            @else
                                <p class="font-medium text-bark text-sm leading-relaxed">{!! $c['value'] !!}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Social Media --}}
            <div class="reveal reveal-delay-1">
                <p class="text-bark/40 text-xs font-bold uppercase tracking-wider mb-3">Ikuti Kami</p>
                <div class="flex gap-3">
                    {{-- Instagram --}}
                    <a href="#" target="_blank"
                       class="flex items-center gap-2 bg-white border border-bark/10 shadow-sm hover:border-pink-300 hover:shadow-md rounded-xl px-4 py-2.5 text-sm font-semibold text-bark/70 hover:text-pink-600 transition-all">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        Instagram
                    </a>
                    {{-- WhatsApp --}}
                    <a href="https://wa.me/6281234567890" target="_blank"
                       class="flex items-center gap-2 bg-white border border-bark/10 shadow-sm hover:border-[#25D366]/50 hover:shadow-md rounded-xl px-4 py-2.5 text-sm font-semibold text-bark/70 hover:text-[#25D366] transition-all">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>

            {{-- Google Maps Placeholder --}}
            <div class="reveal reveal-delay-2">
                <p class="text-bark/40 text-xs font-bold uppercase tracking-wider mb-3">Lokasi Kami</p>
                <div class="relative rounded-2xl overflow-hidden border border-bark/10 shadow-sm" style="height: 240px;">

                    {{-- Shimmer background --}}
                    <div class="absolute inset-0 map-shimmer"></div>

                    {{-- Map grid lines --}}
                    <svg class="absolute inset-0 w-full h-full opacity-20" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#5C3D2E" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#grid)"/>
                        {{-- Simulated roads --}}
                        <line x1="0" y1="120" x2="100%" y2="120" stroke="#5C3D2E" stroke-width="3" stroke-opacity="0.3"/>
                        <line x1="200" y1="0" x2="200" y2="100%" stroke="#5C3D2E" stroke-width="2" stroke-opacity="0.25"/>
                        <line x1="0" y1="60" x2="100%" y2="80" stroke="#5C3D2E" stroke-width="1.5" stroke-opacity="0.15"/>
                        <line x1="350" y1="0" x2="330" y2="100%" stroke="#5C3D2E" stroke-width="1.5" stroke-opacity="0.15"/>
                    </svg>

                    {{-- Center pin + label --}}
                    <div class="absolute inset-0 flex flex-col items-center justify-center gap-2">
                        <div class="bg-white rounded-2xl shadow-xl border border-bark/15 px-5 py-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-ember flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-bark text-sm">Warung Nymphaea</p>
                                <p class="text-bark/45 text-xs">Jl. Melati No. 12, Lumajang</p>
                            </div>
                        </div>
                        <a href="https://maps.google.com/?q=Lumajang,Jawa+Timur"
                           target="_blank"
                           class="bg-bark/80 hover:bg-bark text-cream text-xs font-semibold px-4 py-1.5 rounded-full transition-colors backdrop-blur-sm">
                            Buka di Google Maps →
                        </a>
                    </div>

                    {{-- Top-right badge --}}
                    <div class="absolute top-3 right-3 bg-white/80 backdrop-blur-sm text-bark/50 text-[10px] font-semibold px-2 py-1 rounded-lg border border-bark/10">
                        📍 Simulasi Peta
                    </div>
                </div>
                <p class="text-bark/30 text-xs mt-2 text-center">
                    Ganti dengan embed Google Maps asli di production
                </p>
            </div>

        </div>


        {{-- ════════════════════════════════
             KOLOM KANAN — Form Hubungi Kami
        ════════════════════════════════ --}}
        <div class="reveal">
            <div class="bg-white rounded-3xl border border-bark/8 shadow-sm overflow-hidden">

                {{-- Form header --}}
                <div class="bg-gradient-to-r from-bark to-charcoal px-6 py-6">
                    <h2 class="font-display text-2xl text-cream">
                        Kirim <span class="italic text-saffron">Pesan</span>
                    </h2>
                    <p class="text-cream/50 text-sm mt-1">
                        Pertanyaan, saran, atau kolaborasi? Kami baca semua pesan masuk.
                    </p>
                </div>

                {{-- Form body --}}
                <div class="p-6 space-y-5">

                    {{-- Nama --}}
                    <div>
                        <label class="block text-bark font-semibold text-sm mb-1.5">
                            Nama Lengkap <span class="text-ember">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-bark/30 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input type="text"
                                   placeholder="Contoh: Budi Santoso"
                                   class="form-input w-full pl-10 pr-4 py-3 border border-bark/15 rounded-xl bg-cream/40 text-bark placeholder-bark/30 text-sm font-medium transition-all">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-bark font-semibold text-sm mb-1.5">
                            Alamat Email <span class="text-ember">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 text-bark/30 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email"
                                   placeholder="contoh@email.com"
                                   class="form-input w-full pl-10 pr-4 py-3 border border-bark/15 rounded-xl bg-cream/40 text-bark placeholder-bark/30 text-sm font-medium transition-all">
                        </div>
                    </div>

                    {{-- Subjek --}}
                    <div>
                        <label class="block text-bark font-semibold text-sm mb-1.5">Subjek</label>
                        <div class="relative">
                            <select class="form-input w-full pl-4 pr-10 py-3 border border-bark/15 rounded-xl bg-cream/40 text-bark text-sm font-medium transition-all appearance-none cursor-pointer">
                                <option value="" class="text-bark/40">— Pilih Subjek —</option>
                                <option>Pertanyaan Menu & Harga</option>
                                <option>Pemesanan Khusus / Katering</option>
                                <option>Keluhan / Masukan</option>
                                <option>Kerja Sama & Sponsorship</option>
                                <option>Lainnya</option>
                            </select>
                            <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-bark/30 pointer-events-none">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Pesan --}}
                    <div>
                        <label class="block text-bark font-semibold text-sm mb-1.5">
                            Pesan <span class="text-ember">*</span>
                        </label>
                        <textarea rows="5"
                                  placeholder="Tuliskan pesanmu di sini…"
                                  class="form-input w-full px-4 py-3 border border-bark/15 rounded-xl bg-cream/40 text-bark placeholder-bark/30 text-sm font-medium transition-all resize-none leading-relaxed"></textarea>
                        <p class="text-bark/30 text-xs mt-1">Minimal 10 karakter</p>
                    </div>

                    {{-- Submit Button --}}
                    <button type="button"
                            onclick="handleFormSubmit(this)"
                            class="w-full bg-ember hover:bg-orange-600 active:scale-[0.98] text-cream font-bold py-4 rounded-2xl
                                   shadow-lg hover:shadow-xl transition-all text-sm flex items-center justify-center gap-2.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Kirim Pesan
                    </button>

                    {{-- Atau via WA --}}
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-bark/10"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-3 text-bark/30 text-xs font-semibold">atau</span>
                        </div>
                    </div>

                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Warung%20Nymphaea%2C%20saya%20ingin%20bertanya%20tentang..."
                       target="_blank"
                       class="w-full flex items-center justify-center gap-2.5 bg-[#25D366] hover:bg-[#1ebe5d]
                              active:scale-[0.98] text-white font-bold py-3.5 rounded-2xl
                              shadow-md hover:shadow-lg transition-all text-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Chat Langsung via WhatsApp
                    </a>

                    <p class="text-center text-bark/25 text-xs">
                        Respons rata-rata dalam 1–2 jam di jam kerja
                    </p>
                </div>
            </div>
        </div>

    </div>
</main>


{{-- ═══════════════════════════════════════
     FOOTER
═══════════════════════════════════════ --}}
<footer class="bg-charcoal text-cream pt-12 pb-6 px-4 mt-12">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-10">
            <div class="sm:col-span-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-ember flex items-center justify-center text-cream font-display font-bold text-lg">W</div>
                    <span class="font-display text-cream text-xl">Warung<span class="text-saffron italic">Nymphaea</span></span>
                </div>
                <p class="text-cream/40 text-xs leading-relaxed max-w-xs">
                    Masakan rumahan yang lezat, jujur bahan-bahannya, dan terjangkau untuk semua. Melayani area Lumajang sejak 2019.
                </p>
            </div>
            <div>
                <p class="text-cream/30 text-xs font-bold uppercase tracking-widest mb-4">Navigasi</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('catalog.index') }}" class="text-cream/60 hover:text-saffron transition-colors">🍽️ Menu Hari Ini</a></li>
                    <li><a href="{{ route('catalog.index') }}#about" class="text-cream/60 hover:text-saffron transition-colors">✨ Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="text-saffron font-semibold">📍 Kontak & Lokasi</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-cream/60 hover:text-saffron transition-colors">🛒 Keranjang</a></li>
                </ul>
            </div>
            <div>
                <p class="text-cream/30 text-xs font-bold uppercase tracking-widest mb-4">Hubungi Kami</p>
                <ul class="space-y-2.5 text-sm text-cream/60">
                    <li class="flex items-start gap-2"><span>📍</span><span>Jl. Melati No. 12, Lumajang, Jawa Timur</span></li>
                    <li class="flex items-center gap-2"><span>📱</span><a href="https://wa.me/6281234567890" target="_blank" class="hover:text-saffron">0812-3456-7890</a></li>
                    <li class="flex items-center gap-2"><span>🕐</span><span>Buka: 07.00 – 21.00 WIB</span></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-cream/25">
            <p>© {{ date('Y') }} Warung Nymphaea. Semua hak cipta dilindungi.</p>
            <p>Dibuat dengan ❤️ untuk UMKM Indonesia</p>
        </div>
    </div>
</footer>


<script>
// Mobile menu toggle
const btn   = document.getElementById('mobile-menu-btn');
const menu  = document.getElementById('mobile-menu');
const open  = document.getElementById('icon-open');
const close = document.getElementById('icon-close');
btn?.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    open.classList.toggle('hidden');
    close.classList.toggle('hidden');
});

// Scroll reveal
const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold: 0.15 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Mock form submit feedback
function handleFormSubmit(btn) {
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        Mengirim…
    `;
    setTimeout(() => {
        btn.innerHTML = `✅ Pesan Terkirim! Kami akan segera membalas.`;
        btn.classList.remove('bg-ember', 'hover:bg-orange-600');
        btn.classList.add('bg-sage', 'cursor-default');
    }, 1500);
}
</script>

</body>
</html>
